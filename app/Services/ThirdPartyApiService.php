<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ThirdPartyApiService
{
    protected $baseUrl;
    protected $authCode;

    public function __construct()
    {
        $this->baseUrl = config('services.third_party.api_base_url');
        //$this->authCode = config('services.third_party.auth_code');
        $this->authCode = Auth::user()->auth_code;
    }

    public function getTicketPricingRecord(array $params = [])
    {
        return Http::get(
            $this->baseUrl . '/StaticTicketPricing/getTicketPricingRecord',
            array_merge($params, [
                'AuthCode' => $this->authCode,
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

    public function get(string $endpoint, array $params = [])
    {
        return Http::get(
            $this->baseUrl . '/' . $endpoint,
            array_merge($params, [
                'AuthCode' => $this->authCode,
            ])
        );
    }
}
