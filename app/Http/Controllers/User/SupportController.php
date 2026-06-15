<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Mail\NewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    public function index()
    {
        $settings = Settings::find(1);
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('user.support.index', [
            'title' => 'Support Tickets',
            'settings' => $settings,
            'tickets' => $tickets,
        ]);
    }

    public function create()
    {
        $settings = Settings::find(1);

        return view('user.support.create', [
            'title' => 'New Support Ticket',
            'settings' => $settings,
        ]);
    }

    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
            'priority' => ['required', 'in:low,medium,high'],
        ])->validate();

        $ticket = SupportTicket::create([
            'user_id' => Auth::id(),
            'ticket_id' => SupportTicket::generateTicketId(),
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'user',
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Notify admin via email
        $settings = Settings::find(1);
        $emailBody = "New support ticket #{$ticket->ticket_id} from " . Auth::user()->name . "\n\nSubject: {$request->subject}\n\nMessage:\n{$request->message}";
        try {
            Mail::to($settings->contact_email)->send(new NewNotification($emailBody, "New Support Ticket: {$ticket->ticket_id}", 'Admin'));
        } catch (\Exception $e) {
            Log::error('Support ticket email failed: ' . $e->getMessage());
        }

        \App\Services\NotificationService::notifyAdmin('support', 'New Support Ticket #' . $ticket->ticket_id, Auth::user()->name . ' opened a new ticket: ' . $request->subject, url('admin/dashboard/support-tickets/' . $ticket->ticket_id));

        return redirect()->route('support.show', $ticket->ticket_id)
            ->with('success', 'Your support ticket has been created successfully!');
    }

    public function show($ticketId)
    {
        $settings = Settings::find(1);
        $ticket = SupportTicket::where('ticket_id', $ticketId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $messages = $ticket->messages()->get();

        return view('user.support.show', [
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

        $ticket = SupportTicket::where('ticket_id', $ticketId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($ticket->status === 'closed') {
            return redirect()->back()->with('message', 'This ticket has been closed and cannot receive new replies.');
        }

        SupportMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_type' => 'user',
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $ticket->update(['status' => 'open']);

        // Notify admin
        $settings = Settings::find(1);
        $emailBody = "New reply on ticket #{$ticket->ticket_id} from " . Auth::user()->name . "\n\n{$request->message}";
        try {
            Mail::to($settings->contact_email)->send(new NewNotification($emailBody, "Reply on Ticket: {$ticket->ticket_id}", 'Admin'));
        } catch (\Exception $e) {
            Log::error('Support reply email failed: ' . $e->getMessage());
        }

        \App\Services\NotificationService::notifyAdmin('support', 'New Reply on Ticket #' . $ticket->ticket_id, Auth::user()->name . ' replied to ticket: ' . $ticket->subject, url('admin/dashboard/support-tickets/' . $ticket->ticket_id));

        return redirect()->route('support.show', $ticket->ticket_id)
            ->with('success', 'Your reply has been sent.');
    }
}
