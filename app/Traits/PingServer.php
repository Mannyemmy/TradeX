<?php

namespace App\Traits;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

/**
 * PingServer trait — NEUTRALIZED
 *
 * Previously this trait sent HTTP requests (including license keys and merchant
 * tokens) to an external server at getonlinetrader.pro. This is a phone-home /
 * data exfiltration risk. All methods now return safe no-op responses.
 *
 * If you need external API integration, implement it with your own controlled
 * endpoints and never send credentials to third-party servers.
 */
trait PingServer
{
    public function callServer($action, $url, $data = [])
    {
        Log::warning('PingServer::callServer() called but has been neutralized. No external request was made.', [
            'action' => $action,
            'url' => $url,
        ]);
        return new \GuzzleHttp\Psr7\Response(200, [], json_encode([
            'error' => true,
            'message' => 'External server calls have been disabled for security.',
        ]));
    }

    public function fetctApi(string $url, array $data = [], string $method = 'GET'): Response
    {
        Log::warning('PingServer::fetctApi() called but has been neutralized. No external request was made.', [
            'url' => $url,
            'method' => $method,
        ]);
        return new Response(new \GuzzleHttp\Psr7\Response(200, [], json_encode([
            'error' => true,
            'message' => 'External server calls have been disabled for security.',
        ])));
    }

    public function backWithResponse(Response $response): array
    {
        return [
            'type' => 'message',
            'message' => 'External server calls have been disabled for security.',
        ];
    }
}
