<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ThirdPartyApiMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            try {
                $idToken = session('auth0_id_token');

                if ($idToken) {
                    $apiUrl = env('THIRD_PARTY_API_BASE_URL') . '/api/Auth0Management/UserLogin';

                    $response = Http::timeout(60)
                        ->withHeaders(['Accept' => 'application/json'])
                        ->post($apiUrl . '?' . http_build_query([
                            'userTokenId' => $idToken,
                            'domain' => env('THIRD_PARTY_DOMAIN_URL'),
                        ]));

                    if ($response->successful()) {
                        $data = json_decode($response->body(), true);

                        if (($data['errorCode'] ?? null) === 0) {
                            Session::put('thirdPartyApiData', $data['data']);
                            view()->share('thirdPartyApiData', $data['data']);
                        }
                    }
                }
            } catch (\Throwable $e) {
                logger()->error('ThirdPartyApiMiddleware Error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        return $next($request);
    }
}
