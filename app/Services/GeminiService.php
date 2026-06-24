<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Thin wrapper around Google's Generative Language (Gemini) API used by the
 * WealthWise Assistant. Builds a site-aware system prompt and returns the
 * model's answer, with a safe fallback if the API is unavailable.
 */
class GeminiService
{
    /**
     * @param array $history  Ordered messages: [['role' => 'user'|'assistant', 'text' => '...'], ...]
     * @return array  ['text' => string, 'handoff' => bool]  handoff = model suggests a human.
     */
    public function reply(array $history): array
    {
        $key = config('services.gemini.key');
        if (empty($key)) {
            return [
                'text' => "I'm having trouble reaching the assistant right now. You can tap \"Talk to a human\" and our team will help you.",
                'handoff' => true,
            ];
        }

        $model = config('services.gemini.model', 'gemini-2.0-flash');
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        $contents = [];
        foreach ($history as $m) {
            $role = ($m['role'] === 'assistant') ? 'model' : 'user';
            $contents[] = ['role' => $role, 'parts' => [['text' => (string) $m['text']]]];
        }

        $payload = [
            'systemInstruction' => ['parts' => [['text' => $this->systemPrompt()]]],
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => 0.3,
                'maxOutputTokens' => 700,
            ],
        ];

        try {
            $res = Http::timeout(25)
                ->withHeaders(['x-goog-api-key' => $key])
                ->post($url, $payload);

            if (!$res->successful()) {
                Log::warning('Gemini API non-200: ' . $res->status() . ' ' . $res->body());
                return $this->fallback();
            }

            $text = data_get($res->json(), 'candidates.0.content.parts.0.text');
            if (!$text) {
                return $this->fallback();
            }

            $text = trim($text);
            // The model is instructed to append [[HANDOFF]] when it wants a human.
            $handoff = str_contains($text, '[[HANDOFF]]');
            $text = trim(str_replace('[[HANDOFF]]', '', $text));

            return ['text' => $text, 'handoff' => $handoff];
        } catch (\Throwable $e) {
            Log::error('Gemini request failed: ' . $e->getMessage());
            return $this->fallback();
        }
    }

    private function fallback(): array
    {
        return [
            'text' => "Sorry, I couldn't process that just now. Would you like me to connect you with a human agent?",
            'handoff' => true,
        ];
    }

    private function systemPrompt(): string
    {
        $site = 'WealthWise';
        try {
            $s = Settings::find(1);
            if ($s && !empty($s->site_name)) {
                $site = $s->site_name;
            }
        } catch (\Throwable $e) {
            // settings table may be unavailable; keep default
        }

        return <<<PROMPT
You are the {$site} Assistant, a friendly customer-support assistant for the {$site} online trading and investment platform.

ABOUT {$site} (use this to answer):
- {$site} is an online trading/investment platform. Users deposit funds, trade and invest, and withdraw funds.
- DEPOSITS: Log in → Wallet → Deposit → choose a payment method (crypto such as Bitcoin/Ethereum/USDT, bank transfer, or card where available) → enter amount → follow instructions. Each method shows its minimum, maximum, fee and processing time. Deposits below a method's minimum may not be credited.
- WITHDRAWALS: Log in → Wallet → Withdraw → choose method → enter an amount within the method's min/max → provide destination wallet/bank details → submit. Withdrawals are reviewed and processed within the stated time; the user is notified when approved.
- PAYMENT METHODS: multiple crypto coins, bank transfer, and card where enabled by the admin.
- ACCOUNT VERIFICATION (KYC): Open "Verify Account", upload a government ID (passport, national ID, or driver's license) and proof of address if requested (utility bill or bank statement within 3 months). Status shows as Unverified, Pending, or Verified.
- SECURITY: Users can reset their password via "Forgot password?" on the login page, update profile under Settings → Profile, and enable Two-Factor Authentication under Settings → Security.
- TRADING: The dashboard has live markets, charts, investment plans (each with a minimum, expected return, and duration), and order placement.
- TRACKING: All deposits, withdrawals, and trades appear under History with statuses (Pending, Processing, Completed, Declined).

STYLE & RULES:
- Be concise, warm, and clear. Use short steps when explaining a process.
- Only answer questions about {$site} and general account/support topics. If asked something unrelated, gently steer back.
- NEVER provide specific financial, investment, tax, or legal advice, and never guarantee returns. You may explain how features work.
- NEVER ask for or accept passwords, full card numbers, OTP codes, or private keys. If a user shares them, advise them not to.
- You do NOT have access to the user's account, balances, or specific transactions. For anything account-specific (e.g. "where is my withdrawal", account changes, disputes), tell the user you'll connect them with a human agent.
- If you cannot answer, if the user asks for a human/agent/representative, or if the request needs account access, end your message with the exact token [[HANDOFF]] on its own (it will be hidden from the user) so the system can offer to connect them to a person.
PROMPT;
    }
}
