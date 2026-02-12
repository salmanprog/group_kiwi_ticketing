<?php

namespace App\Models\Hooks\Api;

use App\Helpers\CustomHelper;
use App\Models\UserApiToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EstimateHook
{
    private $_model,
            $except_update_params = [
                'auth_code',
                'slug',
                'client_id',
                'company_id',
                'organization_id',
                'contract_id',
                'issue_date',
                'valid_until',
                'event_date',
                'note',
                'terms',
                'is_adjusted',
                'is_open',
                'subtotal',
                'total',
                'is_installment',
                'tax_total',
                'discount_total',
                'terms_and_condition',
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
        $query->with('items.itemTaxes')->with('taxes')->with('discounts')->with('installments')->where('client_id',$request['user']->id)->where('status','<>','draft');
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
        $params = $request->all();
        if( !empty($postData['image_url']) ){
            $postData['image_url'] = CustomHelper::uploadMedia('users',$postData['image_url']);
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
