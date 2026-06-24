<?php

namespace App\Http\Controllers;

use App\Models\AssistantConversation;
use App\Models\AssistantMessage;
use App\Services\GeminiService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Public-facing WealthWise Assistant endpoints (works for both logged-in users
 * and anonymous visitors on the static marketing pages).
 */
class AssistantController extends Controller
{
    /** Send a message; get an AI reply, unless the chat has been handed to a human. */
    /** Guardrails to protect AI quota/cost from abuse. */
    const MAX_AI_PER_CONVERSATION = 30;   // AI replies in a single chat
    const MAX_USER_MESSAGES_PER_DAY = 60; // user messages per visitor per 24h

    public function message(Request $request, GeminiService $gemini)
    {
        $data = $request->validate([
            'message'         => ['required', 'string', 'max:2000'],
            'guest_id'        => ['nullable', 'string', 'max:64'],
            'conversation_id' => ['nullable', 'integer'],
        ]);

        $text = trim($data['message']);
        if ($text === '') {
            return response()->json(['error' => 'Message is empty'], 422);
        }

        $conv = $this->resolveConversation($request);

        // Anti double-send: ignore an identical message sent seconds apart.
        $lastUser = $conv->messages()->where('sender_type', 'user')->latest('id')->first();
        if ($lastUser && $lastUser->message === $text && $lastUser->created_at && $lastUser->created_at->gt(now()->subSeconds(8))) {
            return response()->json([
                'conversation_id' => $conv->id,
                'handed_off'      => (bool) $conv->handed_off,
                'user_message_id' => $lastUser->id,
                'reply'           => null,
                'duplicate'       => true,
            ]);
        }

        $userMsg = AssistantMessage::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'user',
            'sender_id'       => Auth::guard('web')->id(),
            'message'         => $text,
        ]);
        $conv->last_message_at = now();
        $conv->save();

        // Once a human is involved, the AI stays quiet — the agent replies.
        if ($conv->handed_off) {
            if ($conv->status !== 'closed') {
                $conv->update(['status' => 'pending']);
            }
            return response()->json([
                'conversation_id' => $conv->id,
                'handed_off'      => true,
                'user_message_id' => $userMsg->id,
                'reply'           => null,
            ]);
        }

        // Guardrails: cap AI usage so a user/bot can't burn quota.
        $limitMsg = $this->aiLimitMessage($conv, $request);
        if ($limitMsg !== null) {
            $botMsg = AssistantMessage::create([
                'conversation_id' => $conv->id,
                'sender_type'     => 'assistant',
                'message'         => $limitMsg,
            ]);
            return response()->json([
                'conversation_id' => $conv->id,
                'handed_off'      => false,
                'user_message_id' => $userMsg->id,
                'reply'           => $limitMsg,
                'reply_id'        => $botMsg->id,
                'suggest_handoff' => true,
            ]);
        }

        $history = $conv->messages()
            ->whereIn('sender_type', ['user', 'assistant'])
            ->get()
            ->map(fn ($m) => ['role' => $m->sender_type, 'text' => $m->message])
            ->toArray();

        $ai = $gemini->reply($history);

        $botMsg = AssistantMessage::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'assistant',
            'message'         => $ai['text'],
        ]);

        return response()->json([
            'conversation_id'  => $conv->id,
            'handed_off'       => false,
            'user_message_id'  => $userMsg->id,
            'reply'            => $ai['text'],
            'reply_id'         => $botMsg->id,
            'suggest_handoff'  => (bool) $ai['handoff'],
        ]);
    }

    /** Returns a limit message if a guardrail is hit, otherwise null. */
    private function aiLimitMessage(AssistantConversation $conv, Request $request): ?string
    {
        // Per-conversation AI reply cap.
        $aiCount = $conv->messages()->where('sender_type', 'assistant')->count();
        if ($aiCount >= self::MAX_AI_PER_CONVERSATION) {
            return "We've covered a lot in this chat! To keep things fast, please start a new chat or tap \"Talk to a human\" and our team will help.";
        }

        // Per-visitor daily message cap (across this visitor's conversations).
        $userId  = Auth::guard('web')->id();
        $guestId = $request->input('guest_id');
        $convIds = AssistantConversation::when($userId, fn ($q) => $q->where('user_id', $userId))
            ->when(!$userId && $guestId, fn ($q) => $q->where('guest_id', $guestId))
            ->pluck('id');

        if ($convIds->isNotEmpty()) {
            $dayCount = AssistantMessage::whereIn('conversation_id', $convIds)
                ->where('sender_type', 'user')
                ->where('created_at', '>=', now()->subDay())
                ->count();
            if ($dayCount >= self::MAX_USER_MESSAGES_PER_DAY) {
                return "You've reached today's message limit for the assistant. Please try again later, or tap \"Talk to a human\" to reach our team.";
            }
        }

        return null;
    }

    /** Escalate the conversation to a human agent and notify admins. */
    public function escalate(Request $request)
    {
        $conv = $this->resolveConversation($request, true);

        // Guests must leave a name + email so the agent can follow up.
        if (!$conv->user_id) {
            $data = $request->validate([
                'name'  => ['required', 'string', 'max:120'],
                'email' => ['required', 'email', 'max:191'],
            ]);
            $conv->guest_name = $data['name'];
            $conv->guest_email = $data['email'];
        }

        $conv->handed_off = true;
        $conv->status = 'pending';
        $conv->last_message_at = now();
        $conv->save();

        AssistantMessage::create([
            'conversation_id' => $conv->id,
            'sender_type'     => 'system',
            'message'         => 'You asked to speak with a human agent. A member of our team will reply here shortly.',
        ]);

        try {
            NotificationService::notifyAdmin(
                'support',
                'New live chat request',
                $conv->display_name . ' asked to speak with a human agent.',
                url('admin/dashboard/assistant-chats/' . $conv->id)
            );
        } catch (\Throwable $e) {
            // notification failure shouldn't break the chat
        }

        return response()->json(['ok' => true, 'conversation_id' => $conv->id]);
    }

    /** Poll for new messages (admin/agent replies, system notices) since a given id. */
    public function poll(Request $request)
    {
        $conv = $this->resolveConversation($request, true);
        $after = (int) $request->input('after_id', 0);

        $messages = $conv->messages()
            ->where('id', '>', $after)
            ->get()
            ->map(fn ($m) => [
                'id'     => $m->id,
                'sender' => $m->sender_type,
                'text'   => $m->message,
                'time'   => optional($m->created_at)->toIso8601String(),
            ]);

        return response()->json([
            'conversation_id' => $conv->id,
            'status'          => $conv->status,
            'handed_off'      => (bool) $conv->handed_off,
            'messages'        => $messages,
        ]);
    }

    /**
     * Find the caller's conversation (validating ownership) or create a new one.
     * Ownership = matching logged-in user_id, or matching anonymous guest_id.
     */
    private function resolveConversation(Request $request, bool $requireExisting = false): AssistantConversation
    {
        $userId  = Auth::guard('web')->id();
        $guestId = $request->input('guest_id');
        $convId  = $request->input('conversation_id');

        if ($convId) {
            $conv = AssistantConversation::find($convId);
            if ($conv && (
                ($userId && (int) $conv->user_id === (int) $userId) ||
                (!$userId && $guestId && $conv->guest_id === $guestId)
            )) {
                return $conv;
            }
        }

        abort_if($requireExisting, 404, 'Conversation not found');

        return AssistantConversation::create([
            'user_id'  => $userId,
            'guest_id' => $userId ? null : $guestId,
            'status'   => 'bot',
        ]);
    }
}
