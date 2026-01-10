<?php

namespace App\Models\Hooks\Api;

use App\Helpers\CustomHelper;
use App\Models\UserApiToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserHook
{
    private $_model,
            $except_update_params = [
                'username',
                'slug',
                'email',
                'mobile_no',
                'password',
                'status',
                'is_email_verify',
                'is_mobile_verify',
                'mobile_otp',
                'email_otp',
                'remember_token',
            ];

    public function __construct($model)
    {
        $this->_model = $model;
    }

    /*
   | ----------------------------------------------------------------------
   | Hook for manipulate query of index result
   | ----------------------------------------------------------------------
   | @query   = current sql query
   | @request = laravel http request class
   |
   */
    public function hook_query_index(&$query,$request, $slug=NULL) {
        //Your code here
        $query->select('users.*');
        //check same user
        if( $request['user']->slug == $slug ){
            $query->selectRaw('api_token,device_type,device_token,users.platform_type,users.platform_id')
                ->join('user_api_token AS uat','uat.user_id','=','users.id')
                ->where('uat.api_token',$request['api_token']);
        }
        $query->where('users.user_type','user');
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before add data is execute
    | ----------------------------------------------------------------------
    | @arr
    |
    */
    public function hook_before_add($request,&$postdata)
    {
        if( env('MAIL_SANDBOX') ){
            $postdata['is_email_verify'] = '1';
            $postdata['email_verify_at'] = Carbon::now();
        }
        //set data
        $postdata['user_group_id'] = 2;
        $postdata['user_group_id'] = 1;
        $postdata['username']   = $this->_model::generateUniqueUserName($postdata['name']);
        $postdata['slug']       = $postdata['username'];
        $postdata['password']   = Hash::make($postdata['password']);
        $postdata['created_at'] = Carbon::now();
        if( !empty($request['image_url']) ){
            $postdata['image_url'] =  CustomHelper::uploadMedia('user',$request['image_url']);
        }
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after add public static function called
    | ----------------------------------------------------------------------
    | @record
    |
    */
    public function hook_after_add($request,$record)
    {
        $api_token  = UserApiToken::generateApiToken($record->id,$request->ip(),$request->header('token'),$record->created_at);
        $request['api_token'] = $api_token;
        $request['user']      = $record;
        //insert api token
        \DB::table('user_api_token')
            ->insert([
                'user_id'       => $record->id,
                'api_token'     => $api_token,
                'refresh_token' => NULL,
                'udid'          => $request->header('token'),
                'device_type'   => $request['device_type'],
                'device_token'  => $request['device_token'],
                'platform_type' => !empty($request['platform_type']) ? $request['platform_type'] : 'custom',
                'platform_id'   => !empty($request['platform_id']) ? $request['platform_id'] : NULL,
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->server('HTTP_USER_AGENT'),
                'created_at'    => Carbon::now()
            ]);
        //send verification email
        if( env('VERIFICATION_TYPE') == 'email' && env('MAIL_SANDBOX') == 0 ){
            $mail_params['name'] = $record->name;
            $mail_params['link'] = route('verifyEmail',['name' => encrypt($record->email)]);
            sendMail(
                $record->email,
                'registration',
                'Welcome To '. env('APP_NAME'),
                $mail_params
            );
        }

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before update data is execute
    | ----------------------------------------------------------------------
    | @request  = http request object
    | @postdata = input post data
    | @id       = current id
    |
    */
    public function hook_before_edit($request, $slug, &$postData)
    {
        foreach( $postData as $key => $value ){
            if( in_array($key,$this->except_update_params) )
                unset($postData[$key]);
        }
        if( !empty($postData['image_url']) ){
            $postData['image_url'] = CustomHelper::uploadMedia('users',$postData['image_url']);
            //$blur_image = CustomHelper::getBlurHashImage(Storage::url($postData['image_url']));
            //$postData['blur_image'] = $blur_image;
        }
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after edit public static function called
    | ----------------------------------------------------------------------
    | @request  = Http request object
    | @$slug    = $slug
    |
    */
    public function hook_after_edit($request, $slug) {
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command before delete public static function called
    | ----------------------------------------------------------------------
    | @request  = Http request object
    | @$id      = record id = int / array
    |
    */
    public function hook_before_delete($request, $slug) {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after delete public static function called
    | ----------------------------------------------------------------------
    | @$request       = Http request object
    | @records        = deleted records
    |
    */
    public function hook_after_delete($request,$records) {
        //Your code here

    }

    public function create_cache_signature($request)
    {
        $cache_params = $request->isMethod('post') ? [] : $request->except(['user','api_token']);
        return 'users_api_' . md5(implode('',$cache_params));
    }
}
