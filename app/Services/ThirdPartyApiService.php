<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ThirdPartyApiService
{
    protected $baseUrl;
    protected $authCode;
    protected $auth0UserId;

    public function __construct()
    {
        $this->baseUrl = config('services.third_party.api_base_url');
        //$this->authCode = config('services.third_party.auth_code');
        $this->authCode = Auth::user()?->auth_code;
        $this->auth0UserId = Auth::user()?->auth0_id;
    }

    public function getTicketPricingRecord(array $params = [], $authCode = null)
    {
        return Http::get(
            $this->baseUrl . '/StaticTicketPricing/getTicketPricingRecord',
            array_merge($params, [
                'AuthCode' => $authCode ?? $this->authCode,
            ])
        );
    }

    public function saveTicket(array $data)
    {
        $params = array_merge($data, [
            'AuthCode' => $this->authCode,
        ]);

        return Http::get(
            $this->baseUrl . '/StaticTicketPricing/AddTicketPricing',
            $params
        );
    }

    public function get(string $endpoint, array $params = [], $authCode = null)
    {
        return Http::get(
            $this->baseUrl . '/' . $endpoint,
            array_merge($params, [
                'AuthCode' => $authCode,
            ])
        );
    }

    public function updateUserPassword($password, $confirmPassword)
    {
        $apiUrl = env('THIRD_PARTY_API_BASE_URL').'/api/Auth0Management/ChangeAuth0UserPassword';

       $response = Http::withHeaders([
                    'accept' => '/',
                    'Authorization' => 'Bearer ' . session('auth0_id_token'),
                ])
                ->post($apiUrl . '?' . http_build_query([
                    'authCode'        => Auth::user()->auth_code,
                    'auth0UserId'     => Auth::user()->auth0_id,
                    'password'        => $password,
                    'confirmPassword' => $confirmPassword,
                ]));

        // dd(session('auth0_id_token'));

        return $response->json();
    }
}
