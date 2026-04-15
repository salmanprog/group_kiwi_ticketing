<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Sms\Sms;
use App\Models\User;
use App\Models\{Estimate,UserHoldTickets,UserOrders};
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;
use App\Services\ThirdPartyApiService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserEstimateController extends RestController
{
    private $_gateway;
    protected $apiService;


    public function __construct(Request $request , ThirdPartyApiService $apiService)
    {
        $this->middleware('custom_auth:api')->only(['index','show','update']);
        parent::__construct('Estimate');
        $this->__request     = $request;
        $this->__apiResource = 'Estimate';
        $this->apiService = $apiService;
    }

    /**
     * This function is used to validate restfull routes
     * @param $action
     * @param string $slug
     * @return array
     */
    public function validation($action,$slug=NULL)
    {
        $validator = [];
        $custom_messages = [
            'password.regex' => __('app.password_regex')
        ];
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [],$custom_messages);
                break;
            case 'PUT':
                $custom_messages = [
                    'slug.exists' => __('app.invalid_user')
                ];
                $this->__request->merge(['slug' => $slug]);
                $validator = Validator::make($this->__request->all(), [],$custom_messages);
                break;
        }
        return $validator;
    }

    /**
     * GET Request Hook
     * This function is run before a model load
     * @param $request
     */
    public function beforeIndexLoadModel($request)
    {
        $this->__apiResource = 'Estimate';
    }

    /**
     * @param $request
     * @param $record
     */
    public function afterIndexLoadModel($request,$record)
    {

    }

    /**
     * POST Request Hook
     * This function is run before a model load
     * @param $request
     */
    public function beforeStoreLoadModel($request)
    {
        
    }

    /**
     * @param $request
     * @param $record
     */
    public function afterStoreLoadModel($request,$record)
    {

    }

    /**
     * Get Single Record hook
     * This function is run before a model load
     * @param {object} $request
     * @param {string} $slug
     */
    public function beforeShowLoadModel($request,$slug)
    {
        
    }

    /**
     * @param $request
     * @param $record
     */
    public function afterShowLoadModel($request,$record)
    {

    }

    /**
     * Update Request Hook
     * This function is run before a model load
     * @param {object} $request
     * @param {string} $slug
     */
    public function beforeUpdateLoadModel($request,$slug)
    {
       $estimateExists = Estimate::where('slug', $slug)
                ->where('status', 'approved')
                ->exists();

            if ($estimateExists) {
                $this->__is_error = true;
                return $this->__sendError(
                    __('app.validation_msg'),
                    ['message' => __('app.estimate_already_approved')],
                    400
                );
            }
        $estimateExistss = Estimate::where('slug', $slug)
                ->where('status', 'rejected')
                ->exists();

            if ($estimateExistss) {
                $this->__is_error = true;
                return $this->__sendError(
                    __('app.validation_msg'),
                    ['message' => __('app.estimate_already_rejected')],
                    400
                );
            }
    }

    /**
     * @param $request
     * @param $record
     */
    public function afterUpdateLoadModel($request,$record)
    {

    }

    /**
     * Delete Request Hook
     * This function is run before a model load
     * @param {object} $request
     * @param {string} $slug
     */
    public function beforeDestroyLoadModel($request,$slug)
    {

    }

    /**
     * @param $request
     * @param $slug
     */
    public function afterDestroyLoadModel($request,$slug)
    {

    }

    public function addOrderTicket(Request $request)
    {
        // Log incoming request
        Log::info('addOrderTicket - Incoming Request', $request->all());

        $param_rule['estimate_id'] = 'required|max:255';
        $param_rule['signature'] = 'required';
        
        $response = $this->__validateRequestParams($request->all(), $param_rule);
        if ($this->__is_error) {
            Log::error('addOrderTicket - Validation Failed', ['response' => $response]);
            return $response;
        }

        // Check if the estimate exists in user_hold_tickets
        $checkEstimate = DB::table('user_hold_tickets')->where('estimate_id', $request->estimate_id)->first();
        if (!$checkEstimate) {
            Log::warning('addOrderTicket - Ticket Not Found', ['estimate_id' => $request->estimate_id]);
            $this->__is_error = true;
            return $this->__sendError(
                __('app.validation_msg'),
                ['message' => __('app.ticket_not_found')],
                400
            );
        }

        if ($checkEstimate->is_order == 1) {
            Log::warning('addOrderTicket - Ticket Already Converted to Order', ['estimate_id' => $request->estimate_id]);
            $this->__is_error = true;
            return $this->__sendError(
                __('app.validation_msg'),
                ['message' => __('app.ticket_already_converted_to_order')],
                400
            );
        }

        // Check installment plan
        $installment = \App\Models\InstallmentPlan::with('payments')
            ->where('estimate_id', $request->estimate_id)
            ->first();
        if (!$installment) {
            Log::warning('addOrderTicket - Installment Plan Not Found', ['estimate_id' => $request->estimate_id]);
            $this->__is_error = true;
            return $this->__sendError(
                __('app.validation_msg'),
                ['message' => "Installment plan not found. Please approve the estimate first."],
                400
            );
        }

        // Create payload
        $payload = UserHoldTickets::createOrderPayload($request->all());
        Log::info('addOrderTicket - Payload Created', $payload);

        if (is_array($payload) && isset($payload['error'])) {
            Log::error('addOrderTicket - Payload Error', ['error' => $payload['error']]);
            return $this->__sendError(
                __('app.validation_msg'),
                ['message' => $payload['error']],
                400
            );
        }

        // Call API to create order ticket
        Log::info('addOrderTicket - Calling API createOrderTicket', $payload);
        $record = $this->apiService->createOrderTicket($payload); 

        Log::info('addOrderTicket - API Response', [
            'status' => $record->status(),
            'body'   => $record->json()
        ]);

        if ($record->status() == 200) {
            $responseArray = $record->json();
            if (isset($responseArray['status']['errorCode']) && $responseArray['status']['errorCode'] == 1) {
                Log::error('addOrderTicket - API Returned Error', [
                    'errorCode'    => $responseArray['status']['errorCode'],
                    'errorMessage' => $responseArray['status']['errorMessage']
                ]);
                return $this->__sendError(
                    __('app.validation_msg'),
                    ['message' => $responseArray['status']['errorMessage']],
                    400
                );
            }
        }

        // Update user_hold_tickets
        DB::table('user_hold_tickets')->where('estimate_id', $request->estimate_id)->update(['is_order' => 1]);
        Log::info('addOrderTicket - Updated user_hold_tickets as ordered', ['estimate_id' => $request->estimate_id]);

        // Store order locally
        $orderData = $record->json();
        $order = UserOrders::storeOrder($orderData, $request->estimate_id,$request->signature); 
        Log::info('addOrderTicket - Order Stored Locally', ['order_id' => $order->id ?? null]);

        $response = [
            'code'       => 200,
            'data'       => $order,
            'message'    => __('app.success'),
            'pagination' => null,
        ];

        Log::info('addOrderTicket - Success Response', $response);

        return response()->json($response, 200);
    }

    public function updateInvoice(Request $request)
    {
        // Log the incoming request
        Log::info('updateInvoice - Incoming Request', $request->all());

        $param_rule['invoice_id']             = 'required|max:36';
        $param_rule['estimate_id']            = 'required';
        $param_rule['amount']                 = 'required|numeric';
        $param_rule['status']                 = 'required|string';
        $param_rule['notes']                  = 'required|string';
        $param_rule['paid_date']              = 'required|date|nullable';
        $param_rule['payment_method']         = 'required|string';
        $param_rule['amount_due']             = 'required|numeric|nullable';
        $param_rule['due_date']               = 'required|date|nullable';
        $param_rule['number_of_Installments'] = 'required|numeric|nullable';
        $param_rule['payment_intent_id']      = 'required|string';
        // $param_rule['subscription_id']        = 'required|string';
        
        $response = $this->__validateRequestParams($request->all(), $param_rule);
        if ($this->__is_error) {
            return $response;
        }

        $estimate = Estimate::where('id', $request->estimate_id)->first();
        if (!$estimate) {
            return $this->__sendError(
                __('app.validation_msg'),
                ['message' => 'Estimate not found'],
                404
            );
        }

        $request->merge(['subscription_id' => $estimate->slug]);
        $payload = UserHoldTickets::createUpdateInvoicePayload($request->all());
        Log::info('updateInvoice - Payload Created', $payload);

        if (is_array($payload) && isset($payload['error'])) {
            Log::error('updateInvoice - Payload Error', ['error' => $payload['error']]);
            return $this->__sendError(
                __('app.validation_msg'),
                ['message' => $payload['error']],
                400
            );
        }

        // Call API and log request
        Log::info('updateInvoice - Calling API updateOrderInvoice', [
            'auth_code' => $estimate->auth_code,
            'payload'   => $payload
        ]);

        $response = $this->apiService->updateOrderInvoice($estimate->auth_code, $payload);
        $data = $response->json();

        Log::info('updateInvoice - API Response', $data);

        if ($data['status']['errorCode'] != 0) {
            Log::error('updateInvoice - API Error', [
                'errorCode'    => $data['status']['errorCode'],
                'errorMessage' => $data['status']['errorMessage']
            ]);
            return $this->__sendError(
                __('app.validation_msg'),
                ['message' => $data['status']['errorMessage']],
                400
            );
        }


        Log::info('updateInvoice - Success Response', $data);
        return response()->json(['status' => 'success', 'message' => $data['status']['errorMessage'], 'data' => $data['data']]);
    }

}
