<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityActionLogs;
use App\Services\ActivityActionLogger;

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
            $this->baseUrl . '/Pricing/GetAllProductPrice',
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


    public function holdTicket(string $date, array $body, string $authCode)
    {
        // dd(json_encode($body));
        $startTime = microtime(true);

        $response = Http::withQueryParameters([
                'date' => $date,
                'AuthCode' => $authCode,
            ])
            ->acceptJson()
            ->post($this->baseUrl . '/Pricing/TicketHold', $body);

        $responseTime = round((microtime(true) - $startTime) * 1000, 2);

              ActivityActionLogger::log([
                'auth_code' => $authCode,
                'action' => 'hold_ticket',
                'method' => 'POST',
                'url' => $this->baseUrl . '/Pricing/TicketHold',
                'payload' => json_encode($body),
                'response' => json_encode($response->json()),
                'status' => $response->json()['status']['errorCode'] === 0 ? 'success' : 'error',
                'status_code' => $response->status(),
                'error_message' => $response->json()['status']['errorCode'] === 0 
                    ? null 
                    : $response->json()['status']['errorMessage'],
                'ip' => request()->ip(),
                'response_time' => $responseTime,
            ]);

        return $response;

    }

    public function releaseTicket(array $body, string $authCode)
    {
        $startTime = microtime(true);

        $response = Http::acceptJson()
        ->withQueryParameters(['AuthCode' => $authCode]) 
        ->post($this->baseUrl . '/Pricing/ReleaseHoldsByOrder', [
            'request' => $body 
        ]);
        
        $responseTime = round((microtime(true) - $startTime) * 1000, 2);
        
        ActivityActionLogger::log([
            'auth_code' => $authCode,
            'action' => 'release_ticket',
            'method' => 'POST',
            'url' => $this->baseUrl . '/Pricing/ReleaseHoldsByOrder',
            'payload' => json_encode($body),
            'response' => json_encode($response->json()),
            'status' => $response->json()['status']['errorCode'] === 0 ? 'success' : 'error',
            'status_code' => $response->status(),
            'error_message' => $response->json()['status']['errorCode'] === 0 
                ? null 
                : $response->json()['status']['errorMessage'],
            'ip' => request()->ip(),
            'response_time' => $responseTime,
        ]);
        
        return $response;
    }

    public function getAllProductPrice(array $params = [], $authCode = null)
    {
        return Http::get(
            $this->baseUrl . '/Pricing/GetAllProductPrice',
            array_merge($params, [
                'authcode' => $authCode ?? $this->authCode,
            ])
        );
    }


    public function getCabanaOccupancy($type, $date, $authCode = null)
    {
        
        // dd(json_encode($this->baseUrl));
        return \Http::get(
            $this->baseUrl . '/Pricing/GetCabanaOccupancy',
            [
                'cabanaType' => $type,
                'date'       => $date,
                'Authcode'   => $authCode ?? $this->authCode,
            ]
        );
    }

    public function createOrderTicket(array $data)
    {
        // dd(json_encode($data));
        $response = Http::acceptJson()
            ->contentType('application/json')
            ->post($this->baseUrl . '/Pricing/AddOrder', $data);

            return $response;
    }


    public function updateOrderInvoice($authCode, array $data)
    {
        $url = $this->baseUrl . "/api/InstallmentPlan/update-subscription-invoices?authCode=" . $authCode;
        // dd($data);
        $response = Http::acceptJson()
                ->contentType('application/json')
                ->post($url, $data);

        return $response;
    }

    public function queryOrderSendList($orderId, $filter = 'Sent', $authCode = null)
    {
        $startTime = microtime(true);
        $response = Http::withHeaders([
                'accept' => '*/*',
            ])
            ->get($this->baseUrl . '/Pricing/QueryOrder2', [
                'authCode' => $authCode ?? $this->authCode,
                'orderId' => $orderId,
                'qrSentViaEmailFilter' => $filter,
            ]);

        $responseTime = round((microtime(true) - $startTime) * 1000, 2);
        if($response['status']['errorCode'] === 0) {
            return $response['data']['tickets'] ?? [];
        }else{
            ActivityActionLogger::log([
                'auth_code' => $authCode,
                'action' => 'query_order_send_list',
                'method' => 'GET',
                'url' => $this->baseUrl . '/Pricing/QueryOrder2',
                'payload' => json_encode([
                    [
                        'authCode' => $authCode ?? $this->authCode,
                        'orderId' => $orderId,
                        'qrSentViaEmailFilter' => $filter,
                    ]
                ]),
                'response' => json_encode($response->json()),
                'status' => $response->json()['status']['errorCode'] === 0 ? 'success' : 'error',
                'status_code' => $response->status(),
                'error_message' => $response->json()['status']['errorCode'] === 0 
                    ? null 
                    : $response->json()['status']['errorMessage'],
                'ip' => request()->ip(),
                'response_time' => $responseTime,
            ]);
            return [];
        }

        return null;
    }


    public function sendTicketToRecipient(array $qrCodes, string $recipientName, string $recipientEmail, string $authCode)
    {
        $startTime = microtime(true);

        // Prepare payload
        $body = [
            'authCode'       => $authCode,
            'qrCodes'      => $qrCodes,
            'recipientName'  => $recipientName,
            'recipientEmail' => $recipientEmail,
        ];

        // Make POST request
        $response = Http::acceptJson()
            ->withHeaders([
                'Content-Type' => 'application/json',
                'accept'       => '*/*',
            ])
            ->post($this->baseUrl . '/Pricing/RecordGroupRecipient', $body);

        $responseTime = round((microtime(true) - $startTime) * 1000, 2);

        // Log activity
        ActivityActionLogger::log([
            'auth_code'    => $authCode,
            'action'       => 'send_ticket_to_recipient',
            'method'       => 'POST',
            'url'          => $this->baseUrl . '/Pricing/RecordGroupRecipient',
            'payload'      => json_encode($body),
            'response'     => json_encode($response->json()),
            'status'       => $response->json()['status']['errorCode'] === 0 ? 'success' : 'error',
            'status_code'  => $response->status(),
            'error_message'=> $response->json()['status']['errorCode'] === 0 
                                ? null 
                                : $response->json()['status']['errorMessage'] ?? null,
            'ip'           => request()->ip(),
            'response_time'=> $responseTime,
        ]);

        return $response;
    }
    
}
