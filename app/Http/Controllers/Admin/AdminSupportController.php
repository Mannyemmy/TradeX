<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\Settings;
use App\Mail\NewNotification;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminSupportController extends Controller
{
    public function index(Request $request)
    {
        $settings = Settings::find(1);

        $query = SupportTicket::with('user', 'latestMessage')
            ->orderBy('updated_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_id', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->paginate(15);

        $statusCounts = SupportTicket::selectRaw("status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        $counts = [
            'all' => $statusCounts->sum(),
            'open' => $statusCounts->get('open', 0),
            'answered' => $statusCounts->get('answered', 0),
            'closed' => $statusCounts->get('closed', 0),
        ];

        return view('admin.support.index', [
            'title' => 'Support Tickets',
            'settings' => $settings,
            'tickets' => $tickets,
            'counts' => $counts,
            'currentStatus' => $request->status,
            'currentSearch' => $request->search,
        ]);
    }

    public function show($ticketId)
    {
        $settings = Settings::find(1);
        $ticket = SupportTicket::where('ticket_id', $ticketId)
            ->with('user')
            ->firstOrFail();

        $messages = $ticket->messages()->get();

        return view('admin.support.show', [
            'title' => "Ticket {$ticket->ticket_id}",
            'settings' => $settings,
            'ticket' => $ticket,
            'messages' => $messages,
        ]);
    }

    public function reply(Request $request, $ticketId)
    {
        Validator::make($request->all(), [
            'message' => ['required', 'string', 'min:5'],
        ])->validate();

        $ticket = SupportTicket::where('ticket_id', $ticketId)->firstOrFail();

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'admin',
            'sender_id' => Auth::guard('admin')->id(),
            'message' => $request->message,
        ]);

        $ticket->update(['status' => 'answered']);

        // Notify user via email
        $settings = Settings::find(1);
        $adminName = Auth::guard('admin')->user()->firstName;
        $emailBody = "Your support ticket #{$ticket->ticket_id} has received a reply.\n\nSubject: {$ticket->subject}\n\nReply:\n{$request->message}\n\nLog in to your dashboard to view the full conversation.";
        try {
            Mail::to($ticket->user->email)->send(new NewNotification($emailBody, "Reply to Your Ticket: {$ticket->ticket_id}", $ticket->user->name));
        } catch (\Exception $e) {
            Log::error('Admin support reply email failed: ' . $e->getMessage());
        }

        // In-app notification (bell + sidebar badge)
        $ticket->user->notify(new UserNotification(
            'support',
            'Support Reply',
            'Your ticket #' . $ticket->ticket_id . ' has a new reply.',
            'chat-bubble-left-right',
            route('support.show', $ticket->ticket_id)
        ));

        return redirect()->route('admin.support.show', $ticket->ticket_id)
            ->with('success', 'Reply sent successfully.');
    }

    public function updateStatus(Request $request, $ticketId)
    {
        Validator::make($request->all(), [
            'status' => ['required', 'in:open,answered,closed'],
        ])->validate();

        $ticket = SupportTicket::where('ticket_id', $ticketId)->firstOrFail();
        $ticket->update(['status' => $request->status]);

        return redirect()->route('admin.support.show', $ticket->ticket_id)
            ->with('success', "Ticket status updated to {$request->status}.");
    }
}
