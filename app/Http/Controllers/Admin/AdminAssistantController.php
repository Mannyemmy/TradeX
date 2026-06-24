<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssistantConversation;
use App\Models\AssistantMessage;
use App\Models\Settings;
use App\Services\GeminiService;
use App\Mail\NewNotification;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminAssistantController extends Controller
{
    /** Inbox: conversations that involve a human, newest activity first. */
    public function index(Request $request)
    {
        $settings = Settings::find(1);

        $query = AssistantConversation::with('user')
            ->withCount('messages')
            ->orderByRaw("FIELD(status,'pending','answered','bot','closed')")
            ->orderByDesc('last_message_at')
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // default view focuses on chats that reached a human
            $query->where('handed_off', true);
        }

        $conversations = $query->paginate(20)->withQueryString();

        return view('admin.assistant.index', compact('conversations', 'settings'));
    }

    /** Full thread + reply box. */
    public function show($id)
    {
        $settings = Settings::find(1);
        $conversation = AssistantConversation::with('user')->findOrFail($id);
        $messages = $conversation->messages()->get();

        return view('admin.assistant.show', compact('conversation', 'messages', 'settings'));
    }

    /** Poll for new messages in a thread (so the agent sees live user replies). */
    public function messages($id, Request $request)
    {
        $conversation = AssistantConversation::findOrFail($id);
        $after = (int) $request->input('after_id', 0);

        $messages = $conversation->messages()
            ->where('id', '>', $after)
            ->get()
            ->map(fn ($m) => [
                'id'     => $m->id,
                'sender' => $m->sender_type,
                'text'   => $m->message,
                'time'   => optional($m->created_at)->format('M d, H:i'),
            ]);

        return response()->json([
            'status'   => $conversation->status,
            'messages' => $messages,
        ]);
    }

    /** Agent posts a reply; the user sees it via the widget's polling. */
    public function reply($id, Request $request)
    {
        $request->validate(['message' => ['required', 'string', 'min:1', 'max:4000']]);

        $conversation = AssistantConversation::with('user')->findOrFail($id);

        $msg = AssistantMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type'     => 'admin',
            'sender_id'       => Auth::guard('admin')->id(),
            'message'         => $request->message,
        ]);

        $conversation->update([
            'status'          => 'answered',
            'handed_off'      => true,
            'last_message_at' => now(),
        ]);

        // Best-effort notify the person
        try {
            if ($conversation->user) {
                $conversation->user->notify(new UserNotification(
                    'support',
                    'Live chat reply',
                    'A support agent replied to your chat.',
                    'chat-bubble-left-right',
                    url('/')
                ));
            } elseif ($conversation->guest_email) {
                $settings = Settings::find(1);
                $body = "You have a new reply from our support team:\n\n" . $request->message
                    . "\n\nReturn to the site and open the assistant to continue the conversation.";
                Mail::to($conversation->guest_email)->send(
                    new NewNotification($body, 'Reply from our support team', $conversation->guest_name ?: 'there')
                );
            }
        } catch (\Throwable $e) {
            Log::error('Assistant reply notification failed: ' . $e->getMessage());
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'ok' => true,
                'message' => [
                    'id'     => $msg->id,
                    'sender' => 'admin',
                    'text'   => $msg->message,
                    'time'   => optional($msg->created_at)->format('M d, H:i'),
                ],
            ]);
        }

        return back()->with('success', 'Reply sent.');
    }

    /** Close a conversation. */
    public function close($id, Request $request)
    {
        $conversation = AssistantConversation::findOrFail($id);
        $conversation->update(['status' => 'closed']);

        AssistantMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type'     => 'system',
            'message'         => 'This conversation was closed by support.',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['ok' => true]);
        }
        return back()->with('success', 'Conversation closed.');
    }

    /** Show the AI knowledge-base editor. */
    public function settings()
    {
        $settings = Settings::find(1);
        $knowledge = $settings && !empty($settings->assistant_knowledge)
            ? $settings->assistant_knowledge
            : GeminiService::defaultKnowledge($settings->site_name ?? 'WealthWise');

        return view('admin.assistant.settings', compact('settings', 'knowledge'));
    }

    /** Save the AI knowledge base. */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'assistant_knowledge' => ['nullable', 'string', 'max:20000'],
        ]);

        $settings = Settings::find(1);
        $settings->assistant_knowledge = $request->input('assistant_knowledge');
        $settings->save();

        return back()->with('success', 'Assistant knowledge updated. The AI will use it on the next message.');
    }
}
