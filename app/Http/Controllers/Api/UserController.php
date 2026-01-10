<?php

namespace App\Http\Controllers\Api;

use App\Libraries\Sms\Sms;
use App\Models\User;
use App\Models\UserApiToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;
use App\Libraries\Payment\Payment;

class UserController extends RestController
{
    private $_gateway;

    public function __construct(Request $request)
    {
        $this->middleware('custom_auth:api')->only(['index','show','update','changePassword','userLogout',
            'verifyCode', 'resendCode']);
        parent::__construct('User');
        $this->__request     = $request;
        $this->__apiResource = 'Auth';
        if( env('GATEWAY_TYPE') != 'None' )
            $this->_gateway = Payment::init();
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
                $validator = Validator::make($this->__request->all(), [
                    'name'          => ['required','min:3','max:50','regex:/^([A-Za-z0-9\s])+$/'],
                    'email'         => ['required', 'email',
                        Rule::unique('users')
                    ],
                    'mobile_no'     => [
                        'required',
                        Rule::unique('users'),
                        'regex:/^(\+?\d{1,3}[-])\d{9,11}$/'
                    ],
                    'password'      => ['required','regex:/^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8,150}$/'],
                    'confirm_password' => 'required|same:password',
                    'device_type'  => 'required|in:web,android,ios',
                    'device_token' => 'required',
                ],$custom_messages);
                break;
            case 'PUT':
                $custom_messages = [
                    'slug.exists' => __('app.invalid_user')
                ];
                $this->__request->merge(['slug' => $slug]);
                $validator = Validator::make($this->__request->all(), [
                    'slug'      => 'exists:users,slug,deleted_at,NULL,id,' . $this->__request['user']->id,
                    'name'      => ['min:3','max:50','regex:/^([A-Za-z0-9\s])+$/'],
                    'image_url' => ['sometimes','max:1024'],
                    'country'   => 'min:3|max:50',
                    'state'     => 'min:3|max:50',
                    'city'      => 'min:3|max:50',
                    'zipcode'   => 'min:3|max:50',
                    'address'   => 'min:3|max:50',
                    'latitude'  => 'min:3|max:50',
                    'longitude' => 'min:3|max:50',
                    'online_status' => 'in:0,1',
                ],$custom_messages);
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
        $this->__apiResource = 'PublicUser';
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
        //create payment gateway customer
        if( env('GATEWAY_TYPE') != 'None' ){
            $customerResponse = $this->_gateway->createCustomer([
                'name'  => $request['name'],
                'email' => $request['email'],
            ]);
            if( $customerResponse['code'] != 200 ){
                $this->__is_error = true;
                return $this->__sendError('Gateway Error',['message' => $customerResponse['message']],400);
            }
            //create payment gateway connect account
            $connectAccount = $this->_gateway->createConnectAccount($request['email']);
            if( $connectAccount['code'] != 200 ){
                $this->__is_error = true;
                return $this->__sendError('Gateway Error',['message' => $connectAccount['message']],400);
            }
            $request->merge([
                'gateway_customer_id' => $customerResponse['data']['customer_id'],
                'gateway_connect_id'  => $connectAccount['data']['connect_account_id'],
            ]);
        }

        //mobile verification
        if( env('VERIFICATION_TYPE') == 'mobile' ) {
            if( env('SMS_SANDBOX',1) != 1){
                $sms = new Sms;
                $response = $sms->getInstance()->sendVerificationCode($request['mobile_no']);
                if( $response['code'] != 200 ){
                    $this->__is_error = true;
                    return $this->__sendError(__('app.validation_msg'),['message' => __('app.invalid_mobile_no') ],400);
                } else {
                    if( env('SMS_DRIVER') == 'TeleSign' ){
                        $request->merge(['mobile_otp' => $response['data']->verification_code ]);
                    }
                }
            }
        }
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
        if( $request['user']->slug != $slug ){
            $this->__apiResource = 'PublicUser';
        }
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

    public function verifyCode()
    {
        $request = $this->__request;
        $param_rules['code'] = 'required';
        $response = $this->__validateRequestParams($request->all(),$param_rules);

        if( $this->__is_error )
            return $response;

        if( env('SMS_SANDBOX',1) != 1){
            $sms = new Sms;
            $response = $sms->getInstance()->checkVerification($request['code'],$request['user']->mobile_no);
            if( $response['code'] != 200 ){
                $this->__is_error = true;
                return $this->__sendError(__('app.validation_msg'),['message' => $response['message'] ],400);
            }
        }

        User::updateUser($request['user']->id,
            [ 'is_mobile_verify' => 1 ,
              'mobile_verify_at' => Carbon::now(),
              'mobile_otp' => NULL] );

        //get updated token record
        $user = User::getUserByApiToken($request['api_token']);

        $this->__is_collection = false;
        $this->__is_paginate   = false;

        return $this->__sendResponse($user,200,__('app.otp_verified'));
    }

    public function resendCode()
    {
        $request = $this->__request;
        if( env('SMS_SANDBOX',1) != 1){
            $sms = new Sms;
            $response = $sms->getInstance()->sendVerificationCode($request['user']->mobile_no);
            if( $response['code'] != 200 ){
                $this->__is_error = true;
                return $this->__sendError(__('app.validation_msg'),['message' => __('app.invalid_mobile_no') ],400);
            }else{
                if( env('SMS_DRIVER') == 'TeleSign' ){
                    User::where('id',$request['user']->id)->update(['mobile_otp' => $response['data']->verification_code ]);
                }
            }
        }

        $this->__is_paginate   = false;
        $this->__is_collection = false;

        return $this->__sendResponse($request['user'],200,__('app.resend_otp_msg'));
    }

    public function login()
    {
        $request = $this->__request;
        $param_rule['email']        = 'required|email';
        $param_rule['password']     = 'required';
        $param_rule['device_type']  = 'required|in:android,ios,web';
        $param_rule['device_token'] = 'required';

        $response = $this->__validateRequestParams($request->all(),$param_rule);
        if( $this->__is_error )
            return $response;

        $user = User::getUserByEmail($request['email']);
        if( !isset($user->id) )
            return $this->__sendError(__('app.validation_msg'),['message' => __('app.login_failed_msg')] ,400);
        if( !Hash::check($request['password'],$user->password) )
            return $this->__sendError(__('app.validation_msg'),['message' => __('app.login_failed_msg')] ,400);
        if( $user->status != 1)
            return $this->__sendError(__('app.validation_msg'),['message' => __('app.account_disabled')], 400);
        if( env('MAIL_SANDBOX') == '0' && $user->is_email_verify != 1){
            return $this->__sendError(__('app.validation_msg'),['message' => __('app.email_not_verified')], 400);
        }

        //update device token
        $api_token = User::updateDeviceToken($request,$user);
        //get updated token record
        $user = User::getUserByApiToken($api_token);

        $this->__is_collection = false;
        $this->__is_paginate   = false;
        return $this->__sendResponse($user,200,__('app.login_success_msg'));
    }

    public function forgotPassword()
    {
        $request = $this->__request;
        $param_rule['email'] = 'required|email';

        $response = $this->__validateRequestParams($request->all(),$param_rule);
        if( $this->__is_error )
            return $response;

        $record = User::ForgotPassword($request['email']);
        if( $record == false )
            return $this->__sendError(__('app.validation_msg'),['message' => __('app.invalid_email')], 400);

        $this->__is_collection = false;
        $this->__is_paginate   = false;
        return $this->__sendResponse($record,200,__('app.login_success_msg'));
    }

    public function changePassword()
    {
        $request = $this->__request;
        $custom_messages = [
            'password.regex' => __('app.password_regex')
        ];
        $param_rule['current_password'] = 'required';
        $param_rule['new_password']     = [
            'required',
            'different:current_password',
            'regex:/^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8,150}$/'
        ];
        $param_rule['confirm_password'] = 'required|same:new_password';

        $response = $this->__validateRequestParams($request->all(),$param_rule,$custom_messages);
        if( $this->__is_error )
            return $response;

        if( !Hash::check($request['current_password'],$request['user']->password) )
            return $this->__sendError(__('app.validation_msg'),['message' => __('app.invalid_old_password') ]);

        //update user new password
        User::updateUser($request['user']->id,['password' => Hash::make($request['new_password'])]);
        //delete api token
        UserApiToken::where('api_token','!=',$request['api_token'])->where('user_id',$request['user']->id)->forceDelete();

        $this->__is_paginate   = false;
        $this->__is_collection = false;

        return $this->__sendResponse($request['user'],200,__('app.password_success_msg'));
    }

    public function socialLogin()
    {
        $request     = $this->__request;
        $param_rule['platform_id']   = 'required|max:255';
        $param_rule['platform_type'] = 'required|in:facebook,google,apple';
        $param_rule['device_type']   = 'required|in:android,ios';
        $param_rule['device_token']  = 'required';
        $param_rule['image_url']     = 'nullable|url';

        $response = $this->__validateRequestParams($request->all(),$param_rule);
        if( $this->__is_error )
            return $response;

        $user = User::socialUser($request->all());
        //update device token
        $api_token = User::updateDeviceToken($request,$user,$request['platform_type']);
        //get updated token record
        $user = User::getUserByApiToken($api_token);

        if( $user->status != 1 ){
            return $this->__sendError(__('app.validation_msg'),['message' => __('app.account_disabled')], 400);
        }

        $this->__is_collection = false;
        $this->__is_paginate   = false;
        return $this->__sendResponse($user,200,__('app.login_success_msg'));
    }

    public function userLogout()
    {
        $request = $this->__request;
        User::userLogout($request->all());

        $this->__collection  = false;
        $this->__is_paginate = false;
        return $this->__sendResponse([],200,__('app.logout_msg'));
    }
}
