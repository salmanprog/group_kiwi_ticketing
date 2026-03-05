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
        $param_rule['estimate_id']   = 'required|max:255';
        $payment_method = $request->payment_method ?? "online";
        if($payment_method == "online") {
            $param_rule['cardholderName']   = 'required|max:255';
            $param_rule['billingStreet']   = 'required|max:255';
            $param_rule['billingZipCode']   = 'required|max:255';
            $param_rule['expDate']   = 'required|max:255';
            $param_rule['paymentCode']   = 'required|max:255';
            $param_rule['amount']   = 'required';
            $param_rule['StaffTip']   = 'required';
            $param_rule['Tax']   = 'required';
            $param_rule['ServiceCharges']   = 'required';
            $param_rule['TransactionId']   = 'required|max:255';
            $param_rule['ccNumber']   = 'required|max:255';
            $param_rule['cvn']   = 'required|max:255';
            $param_rule['PaymentMethodId']   = 'required|max:255';
        }
        
        $response = $this->__validateRequestParams($request->all(),$param_rule);
        if( $this->__is_error )
            return $response;

        $checkEstimate = DB::table('user_hold_tickets')->where('estimate_id', $request->estimate_id)->first();
        if(!$checkEstimate) {
            $this->__is_error = true;
            return $this->__sendError(
                __('app.validation_msg'),
                ['message' => __('app.ticket_not_found')],
                400
            );
        }

        if($checkEstimate->is_order == 1) {
            $this->__is_error = true;
            return $this->__sendError(
                __('app.validation_msg'),
                ['message' => __('app.ticket_already_converted_to_order')],
                400
            );
        }

        // dd($checkEstimate);

        $payload = UserHoldTickets::createOrderPayload($request->all());


        $record = $this->apiService->createOrderTicket($payload);

        if ($record->status() == 200) {
            $responseArray = $record->json();
            if (isset($responseArray['status']['errorCode']) && $responseArray['status']['errorCode'] == 1) {
                return $this->__sendError(
                    __('app.validation_msg'),
                    ['message' => $responseArray['status']['errorMessage']],
                    400
                );
            }
        }

        DB::table('user_hold_tickets')->where('estimate_id', $request->estimate_id)->update(['is_order' => 1]);

        $orderData = $record->json();

        // $orderData = [
        //         "sessionId" => "",
        //         "status" => [
        //             "errorCode" => 0,
        //             "errorMessage" => ""
        //         ],
        //         "data" => [
        //             [
        //                 "visualId" => "101-040326184643552-181358-936612",
        //                 "childVisualId" => "",
        //                 "parentVisualId" => "",
        //                 "ticketType" => "wave-pool-blue-cabanas",
        //                 "ticketSlug" => "",
        //                 "description" => "wave-pool-blue-cabanas",
        //                 "seat" => "",
        //                 "price" => 0,
        //                 "ticketDate" => "2026-06-15T00:00:00",
        //                 "ticketDisplayDate" => "6-15-2026",
        //                 "orderDate" => "2026-03-04T18:46:43",
        //                 "orderDisplayDate" => "3-4-2026",
        //                 "firstName" => "",
        //                 "lastName" => "",
        //                 "email" => "",
        //                 "phone" => "",
        //                 "orderTotal" => 1056,
        //                 "paidAmount" => 0,
        //                 "orderTip" => 2,
        //                 "orderNumber" => "21",
        //                 "quantity" => 1
        //             ],
        //             [
        //                 "visualId" => "101-040326184643675-181358-723925",
        //                 "ticketType" => "wave-pool-blue-cabanas",
        //                 "description" => "wave-pool-blue-cabanas",
        //                 "price" => 0,
        //                 "quantity" => 1
        //             ],
        //             [
        //                 "visualId" => "101-040326184643794-181358-660289",
        //                 "ticketType" => "wave-pool-blue-cabanas",
        //                 "description" => "wave-pool-blue-cabanas",
        //                 "price" => 0,
        //                 "quantity" => 1
        //             ],
        //             [
        //                 "visualId" => "101-040326184643882-181358-608111",
        //                 "ticketType" => "wave-pool-blue-cabanas",
        //                 "description" => "wave-pool-blue-cabanas",
        //                 "price" => 0,
        //                 "quantity" => 1
        //             ],
        //         ]
        //     ];

        
          $order = UserOrders::storeOrder($orderData, $request->estimate_id);
          $response = [
            'code'       => 200,
            'data'       => $order,
            'message'    => __('app.success'),
            'pagination' => null,
        ];

        return response()->json($response, 200);
    }



}
