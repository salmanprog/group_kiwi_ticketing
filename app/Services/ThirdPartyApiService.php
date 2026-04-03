<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityActionLogs;
use App\Services\ActivityActionLogger;
use Illuminate\Support\Facades\Mail;

class ThirdPartyApiService
{
    protected $baseUrl;
    protected $authCode;
    protected $auth0UserId;

    public function __construct()
    {
        $this->baseUrl = config('services.third_party.api_base_dev_env');
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
        $apiUrl = env('THIRD_PARTY_DEV_API_BASE_URL').'/api/Auth0Management/ChangeAuth0UserPassword';

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

        // if($response->json()['status']['errorCode'] !== 0) {
            $companyName = DB::table('company')->where('auth_code', $authCode)->value('name') ?? 'Unknown Company';
            $this->sendOrderFailedEmail($body, $response->json(),'Ticket Hold Failed', $companyName);
        // }

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
        $startTime = microtime(true);
        // dd(json_encode($data['AuthCode']));
        $response = Http::acceptJson()
            ->contentType('application/json')
            ->post($this->baseUrl . '/Pricing/AddOrder', $data);

        $responseTime = round((microtime(true) - $startTime) * 1000, 2);

        // Log activity
        ActivityActionLogger::log([
            'auth_code'    => $data['AuthCode'],
            'action'       => 'create_order_ticket',
            'method'       => 'POST',
            'url'          => $this->baseUrl . '/Pricing/AddOrder',
            'payload'      => json_encode($data),
            'response'     => json_encode($response->json()),
            'status'       => $response->json()['status']['errorCode'] === 0 ? 'success' : 'error',
            'status_code'  => $response->status(),
            'error_message'=> $response->json()['status']['errorCode'] === 0 
                                ? null 
                                : $response->json()['status']['errorMessage'] ?? null,
            'ip'           => request()->ip(),
            'response_time'=> $responseTime,
        ]);

        if($response->json()['status']['errorCode'] !== 0) {
            $companyName = DB::table('company')->where('auth_code', $data['AuthCode'])->value('name') ?? 'Unknown Company';
            $this->sendOrderFailedEmail($data, $response->json(),'Order Add Failed', $companyName);
        }
        
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

    public function UpdateOrderSettings(string $orderNumber, int $isAllowedForPrinting, string $authCode)
    {
        $startTime = microtime(true);

        // Prepare payload
        $body = [
            'orderNumber'  => $orderNumber,
            'isAllowedForPrinting' => $isAllowedForPrinting,
        ];

        // Make POST request
        $response = Http::acceptJson()
            ->withHeaders([
                'Content-Type' => 'application/json',
                'accept'       => '*/*',
            ])
            ->post($this->baseUrl . '/api/OrderSettings/UpdateOrderSettings?authCode=' . $authCode, $body);

            
        // dd($response->json());
        $responseTime = round((microtime(true) - $startTime) * 1000, 2);

        // Log activity
        ActivityActionLogger::log([
            'auth_code'    => $authCode,
            'action'       => 'update_order_settings',
            'method'       => 'POST',
            'url'          => $this->baseUrl . '/Pricing/UpdateOrderSettings',
            'payload'      => json_encode($body),
            'response'     => json_encode($response->json()),
            'status'       => $response->json()['errorCode'] === 0 ? 'success' : 'error',
            'status_code'  => $response->status(),
            'error_message'=> $response->json()['errorCode'] === 0 
                                ? null 
                                : $response->json()['errorMessage'] ?? null,
            'ip'           => request()->ip(),
            'response_time'=> $responseTime,
        ]);

        return $response;
    }
    
   private function sendOrderFailedEmail(array $payload, array $response, string $errorMessage, string $companyName)
    {
        $adminEmail = [
            'dev@ideaseat.com',
            'ali@yopmail.com',
            'syedarhamkingdomvision@gmail.com'
        ];
    

        foreach($adminEmail as $email) {
            $data = [
                'company_name' => $companyName,
                'error_message' => $errorMessage,
                'payload' => $payload,
                'response' => $response,
            ];

            Mail::send('email.order_failed', $data, function($message) use ($email) {
                $message->to($email)
                        ->subject('Order Creation Failed');
            });
        }
     
    }


    
}
