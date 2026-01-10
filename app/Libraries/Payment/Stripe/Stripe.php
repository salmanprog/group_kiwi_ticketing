<?php

namespace App\Libraries\Payment\Stripe;

use Illuminate\Support\Facades\URL;

class Stripe
{
    private $_response, $_stripe;

    public function __construct()
    {
        $this->_stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        $this->_response = [
            'code'    => 200,
            'message' => 'success',
            'data'    => [],
            'gateway_response' => []
        ];
    }

    /**
     * Reference Link: https://stripe.com/docs/api/customers/create?lang=php
     * @param array $data
     * @return array
     */
    public function createCustomer($data)
    {
        try{
            $customer = $this->_stripe->customers->create($data);
        }catch (\Exception $e){
            return $this->_response = [
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
        }
        return $this->_response = [
            'code'    => 200,
            'message' =>'Customer added successfully',
            'data' => [
                'customer_id' => $customer->id
            ],
            'gateway_response' => $customer
        ];
    }

    /**
     * Reference Link: https://stripe.com/docs/api/customers/update?lang=php
     * @param string $customer_id
     * @param string $card_token
     * @return array
     */
    public function createCustomerCard($customer_id,$card_token)
    {
        try{
            $card = $this->_stripe->customers->createSource(
                $customer_id,
                ['source' => $card_token]
            );
        }catch (\Exception $e){
            return $this->_response = [
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
        }
        return $this->_response = [
            'code'    => 200,
            'message' =>'Customer card created successfully',
            'data' => [
                'card_id' => $card->id
            ],
            'gateway_response' => $card
        ];
    }

    /**
     * Reference Link: https://stripe.com/docs/api/customers/update?lang=php
     * @param string $customer_id
     * @param string $card_id
     * @return array
     */
    public function makeDefaultCard($customer_id, $card_id)
    {
        try{
            $customer = $this->_stripe->customers->update(
                $customer_id,
                ['default_source' => $card_id]
            );
        }catch (\Exception $e){
            return $this->_response = [
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
        }
        return $this->_response = [
            'code'    => 200,
            'message' =>'Customer card updated successfully',
            'data'    => $customer,
            'gateway_response' => $customer
        ];
    }

    /**
     * Reference Link: https://stripe.com/docs/api/cards/delete?lang=php
     * @param {string} $customer_id
     * @param {string} $card_id
     * @return array
     */
    public function deleteCustomerCard($customer_id, $card_id)
    {
        try{
            $customer = $this->_stripe->customers->deleteSource(
                $customer_id,
                $card_id,
                []
            );
        }catch (\Exception $e){
            return $this->_response = [
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
        }
        return $this->_response = [
            'code'    => 200,
            'message' =>'Customer card deleted successfully',
            'data'    => $customer,
            'gateway_response' => $customer
        ];
    }

    /**
     * Reference Link: https://stripe.com/docs/api/charges/create
     * @param string $customer_id
     * @param float $amount
     * @param string $currency
     * @param string $description
     * @return array
     */
    public function customerCharge($customer_id, $amount, $capture = true, $currency = 'usd', $description = '')
    {
        try{
            $transaction = $this->_stripe->charges->create([
                'amount'      => round($amount * 100,2),
                'currency'    => $currency,
                'customer'    => $customer_id,
                'description' => $description,
                'capture'     => $capture
            ]);
        }catch (\Exception $e){
            return $this->_response = [
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
        }
        return $this->_response = [
            'code'    => 200,
            'message' =>'Customer has been charged successfully',
            'data'    => [
                'transaction_id' => $transaction->id
            ],
            'gateway_response' => $transaction
        ];
    }
    /**
     * Reference Link: https://stripe.com/docs/api/charges/capture?lang=php
     * @param string $charge_id
     * @return array
     */
    public function captureCharge($charge_id)
    {
        try{
            $capture = $this->_stripe->charges->capture($charge_id,[]);
        }catch (\Exception $e){
            return $this->_response = [
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
        }
        return $this->_response = [
            'code'    => 200,
            'message' =>'Transaction has been captured successfully',
            'data'    => $capture
        ];
    }

    /**
     * Reference Link: https://stripe.com/docs/api/charges/create
     * @param string $card_token
     * @param float $amount
     * @param string $currency
     * @param string $description
     * @return array
     */
    public function directCharge($card_token, $amount, $currency = 'usd', $description = '')
    {
        try{
            $transaction = $this->_stripe->charges->create([
                'amount'      => round($amount * 100,2),
                'currency'    => $currency,
                'source'      => $card_token,
                'description' => $description,
            ]);
        }catch (\Exception $e){
            return $this->_response = [
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
        }
        return $this->_response = [
            'code'    => 200,
            'message' =>'payment has been charged successfully',
            'data'    => [
                'transaction_id' => $transaction->id
            ],
            'gateway_response' => $transaction
        ];
    }

    public function createConnectAccount($email)
    {
        try{
            $connect = $this->_stripe->accounts->create([
                'type' => 'custom',
                'country' => 'US',
                'email' => $email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                "tos_acceptance" => [
                    'date' => time(),
                    'ip' => $_SERVER['REMOTE_ADDR'],
               ],
            ]);
        }catch (\Exception $e){
            return $this->_response = [
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
        }
        return $this->_response = [
            'code'    => 200,
            'message' =>'Connect account added successfully',
            'data' => [
                'connect_account_id' => $connect->id
            ],
            'gateway_response' => $connect
        ];
    }

    public function getConnectAccount($connect_account_id)
    {
        try{
            $connect = $this->_stripe->accounts->retrieve($connect_account_id,[]);
        }catch (\Exception $e){
            return $this->_response = [
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
        }
        return $this->_response = [
            'code'    => 200,
            'message' => 'Connect account retrieved successfully',
            'data'    => $connect
        ];
    }

    public function updateConnectAccount($connect_account_id, $data)
    {
        $connect_account_param['business_profile']['mcc'] = env('CONNECT_ACCOUNT_BUSINESS_CODE');
        $connect_account_param['business_profile']['url'] = env('CONNECT_ACCOUNT_BUSINESS_URL');
        $connect_account_param['individual'] = [];
        if( !empty($data['first_name']) ){
            $connect_account_param['individual']['first_name'] = $data['first_name'];
        }
        if( !empty($data['last_name']) ){
            $connect_account_param['individual']['last_name'] = $data['last_name'];
        }
        if( !empty($data['email']) ){
            $connect_account_param['individual']['email'] = $data['email'];
        }
        if( !empty($data['day']) && !empty($data['month']) && $data['year'] ){
            $connect_account_param['individual']['dob']['day']   = $data['day'];
            $connect_account_param['individual']['dob']['month'] = $data['month'];
            $connect_account_param['individual']['dob']['year']  = $data['year'];
        }
        if( !empty($data['street']) && !empty($data['city']) &&
            !empty($data['postal_code']) && $data['state'] )
        {
            $connect_account_param['individual']['address']['line1']        = $data['street'];
            $connect_account_param['individual']['address']['city']         = $data['city'];
            $connect_account_param['individual']['address']['postal_code']  = $data['postal_code'];
            $connect_account_param['individual']['address']['state']        = $data['state'];
        }
        if( !empty($data['phone']) ){
            $connect_account_param['individual']['phone'] = $data['phone'];
        }
        if( !empty($data['file_upload_back_id']) && !empty($data['file_upload_front_id']) ){
            $connect_account_param['individual']['verification']['document']['back'] = $data['file_upload_back_id'];
            $connect_account_param['individual']['verification']['document']['front'] = $data['file_upload_front_id'];
        }
        if( !empty($data['ssn']) ){
            $connect_account_param['individual']['id_number']  = $data['ssn'];
            $connect_account_param['individual']['ssn_last_4'] = $data['ssn_last_4'];
        }
        try{
            $connectAccount = $this->_stripe->accounts->update($connect_account_id,$connect_account_param);
        }catch(\Exception $e){
            return $this->_response = [
                'code'    => 400, //status code
                'message' => $e->getMessage(), //message
            ];
        }
        return $this->_response = [
            'code'    => 200, //status code
            'message' => 'Connect account updated successfully', //message
            'data' => [
                'connect_account' => $connectAccount
            ] //data
        ];
    }

    public function createExternalAccount($connect_account_id,$token, $make_default = false)
    {
      try{
          $data['external_account'] = $token;
          if( $make_default ){
              $data['default_for_currency'] = $make_default;
          }
          $account = $this->_stripe->accounts->createExternalAccount($connect_account_id,$data);
      }catch(\Exception $e){
        return $this->_response = [
                'code'    => 400, //status code
                'message' => $e->getMessage(), //message
            ];
      }
      return $this->_response = [
            'code'    => 200, //status code
            'message' => 'Connect external account created successfully', //message
            'data' => [
                'connect_account' => $account
            ] //data
        ];
    }

    public function makeDefaultExternalAccount($connect_account_id, $external_account_id)
    {
        try{
            $connectAccount = $this->_stripe->accounts->updateExternalAccount(
                    $connect_account_id,
                    $external_account_id,
                    ['default_for_currency' => true]
                );
        }catch(\Exception $e){
            return $this->_response = [
                'code'    => 400, //status code
                'message' => $e->getMessage(), //message
            ];
        }
        return $this->_response = [
            'code'    => 200, //status code
            'message' => 'External account updated successfully', //message
            'data' => [
                'connect_account' => $connectAccount
            ] //data
        ];
    }

    public function deleteExternalAccount($connect_account_id,$bank_id)
    {
        try{
            $record = $this->_stripe->accounts->deleteExternalAccount(
                $connect_account_id,
                $bank_id
            );
        }catch(\Exception $e){
            return $this->_response = [
                'code'    => 400, //status code
                'message' => $e->getMessage(), //message
            ];
        }
        return $this->_response = [
            'code'    => 200, //status code
            'message' => 'External account deleted successfully', //message
            'data'    => $record
        ];
    }

    public function updateExternalBankAccount($connect_account_id,$bank_id,$data)
    {
        try{
            $account = $this->_stripe->accounts->updateExternalAccount($connect_account_id,$bank_id,$data);
        }catch(\Exception $e){
            return $this->_response = [
                'code'    => 400, //status code
                'message' => $e->getMessage(), //message
            ];
        }
        return $this->_response = [
            'code'    => 200, //status code
            'message' => 'Bank account deleted successfully', //message
            'data' => [
                'bank_account' => $account
            ] //data
        ];
    }

    public function transfer($data)
    {
        try{
            $transfer = $this->_stripe->transfers->create(array(
                "amount"             => round($data['amount'] * 100, 2),
                "currency"           => env('STRIPE_CHARGE_CURRENCY'),
                "source_transaction" => $data['charge_id'],
                "destination"        => $data['destination'],//'acct_1EdE1NLBq37AWvff',
            ));
        }catch(\Exception $e){
            return $this->_response = [
                'code'    => 400, //status code
                'message' => $e->getMessage(), //message
            ];
        }
        return $this->_response = [
            'code'    => 200, //status code
            'message' =>'Transaction completed successfully', //message
            'data' => [
                'transfer' => $transfer
            ] //data
        ];
    }

    public function uploadFile($file_path)
    {
        try{
            $fp = fopen($file_path, 'r');
            $response = $this->_stripe->files->create([
                            'purpose' => 'identity_document',
                            'file' => $fp
                        ]);
        }catch (\Exception $e){
            return $this->_response = [
                'code'    => 400, //status code
                'message' => $e->getMessage(), //message
            ];
        }
        return $this->_response = [
            'code'    => 200, //status code
            'message' =>'file uploaded successfully', //message
            'data'    => $response
        ];
    }

    public function createConnectOnBoardLink($connect_account_id)
    {
        try{
            $response = $this->_stripe->accountLinks->create([
                'account'     => $connect_account_id,
                'refresh_url' => URL::to('/'),
                'return_url'  => URL::to('/'),
                'type'        => 'account_update',
            ]);
        } catch (\Exception $e){
            return $this->_response = [
                'code'    => 400, //status code
                'message' => $e->getMessage(), //message
            ];
        }
        return $this->_response = [
            'code'    => 200, //status code
            'message' =>'Account link has been generated successfully', //message
            'data' => [
                'account_link' => $response->url
            ] //data
        ];
    }
}
