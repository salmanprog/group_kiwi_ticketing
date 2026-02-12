<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Sms\Sms;
use App\Models\User;
use App\Models\{Estimate,Company};
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;
use DB;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class StripeController extends RestController
{
    private $_gateway;

    public function __construct(Request $request)
    {
        $this->middleware('custom_auth:api')->only(['createPaymentIntent']);
        parent::__construct('Invoice');
        $this->__request     = $request;
        $this->__apiResource = 'Invoice';
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
        $this->__apiResource = 'Invoice';
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
    //    $estimateExists = Estimate::where('slug', $slug)
    //             ->where('status', 'approved')
    //             ->exists();

    //         if ($estimateExists) {
    //             $this->__is_error = true;
    //             return $this->__sendError(
    //                 __('app.validation_msg'),
    //                 ['message' => __('app.estimate_already_approved')],
    //                 400
    //             );
    //         }
    //     $estimateExistss = Estimate::where('slug', $slug)
    //             ->where('status', 'rejected')
    //             ->exists();

    //         if ($estimateExistss) {
    //             $this->__is_error = true;
    //             return $this->__sendError(
    //                 __('app.validation_msg'),
    //                 ['message' => __('app.estimate_already_rejected')],
    //                 400
    //             );
    //         }
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


      public function createPaymentIntent(Request $request)
    {
        $request     = $this->__request;
        $param_rule['invoice_id']   = 'required';
        $param_rule['invoice_type'] = 'required|in:invoice,installment';
        $param_rule['user_id']  = 'required|exists:users,id';
        $param_rule['company_id']     = 'required';
        $param_rule['amount']     = 'required';
        $param_rule['name']     = 'required';

        $response = $this->__validateRequestParams($request->all(),$param_rule);
        if( $this->__is_error )
            return $response;


        $company = Company::getCompanyAdmin($request->company_id);
          if ($company->stripe_key_status == 'test') {
            if (!$company->test_publishable_key || !$company->test_secret_key) {
                return $this->__sendError('Company Stripe test keys not set.', [], 400);
            }
        } else {
            if (!$company->live_publishable_key || !$company->live_secret_key) {
                return $this->__sendError('Company Stripe live keys not set.', [], 400);
            }
        }
               
        
        try {
            if ($company->stripe_key_status == 'test') { 
                Stripe::setApiKey($company->test_secret_key);
            } else {
                Stripe::setApiKey($company->live_secret_key);
            }

            if($request->invoice_type == 'invoice') {
                $invoice = Invoice::find($request->invoice_id);
                $description = 'Kiwi Group # Invoice ' . ($invoice->invoice_number ?? 'N/A') . ' from '. $request->name;
            } else {
                $installment_payments = DB::table('installment_payments')
                ->join('installment_plans', 'installment_payments.installment_plan_id', '=', 'installment_plans.id')
                ->join('user_invoices', 'installment_plans.invoice_id', '=', 'user_invoices.id')
                ->where('installment_payments.id', $request->invoice_id)
                ->first();
               
                $description = 'Kiwi Group # Installment ' . ($installment_payments->installment_number ?? 'N/A') . ' - ' . ($installment_payments->invoice_number ?? 'N/A') . ' from '. $request->name;
            }


            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($request->amount * 100),
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'description' => $description,

                'metadata' => [
                    'customer_name' => $request->name,
                ],
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'Payment Intent created successfully.',
                'data' => [
                    'name' => $request->name,
                    'clientSecret' => $paymentIntent->client_secret
                ]
            ], 200);

        } catch (ApiErrorException $e) {
            return $this->__sendError('Company Stripe Error.', [$e->getMessage()], 400);

        } catch (\Exception $e) {
            return $this->__sendError('Company Stripe Error.', [$e->getMessage()], 400);
        }
    }

}
