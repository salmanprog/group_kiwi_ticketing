<?php

namespace App\Models\Hooks\Api;
use App\Models\ContractModified;
use App\Models\ActivityLog;

class ContractModifiedHook
{
    private $_model;

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
        $query->select('contract_modified.*');
        $query->join('contracts', 'contracts.id', '=', 'contract_modified.contract_id');
        $query->with([
            'createdBy',
            'contract',
            'items.itemTaxes',
            'taxes',
            'discounts',
            'installments',
            'contract.client',
            'contract.company',
            'contract.organization',
            'contract.client',
            'contract.estimateone'
        ])
        ->where('contract_modified.status', '!=', 'pending');
        $query->where('contracts.client_id', $request['user']['id']);
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
        //Your code here
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
        if($request->status == 'rejected'){
          $postData['status'] = 'reject';
        }else{
          $postData['status'] = 'accept_by_client';
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
        if($request->status == 'rejected'){
          $postData['status'] = 'reject';
        }else{
          $postData['status'] = 'accept_by_client';
          $contractModified = ContractModified::where('slug', $slug)->first();
          $data = [
            'contract_modified_id' => $contractModified->id,
            'contract_id' => $contractModified->contract_id
          ];
          ContractModified::updateModifyOrder($data);

            ActivityLog::create([
                'module' => 'contract',
                'module_id' => $contractModified->contract_id,
                'description' => "Contract modify {$contractModified->slug} accepted by client",
                'user_id' => $request['user']->id,
            ]);
        }
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
        $cache_params = $request->except(['user','api_token']);
        return 'ContractModifiedHook' . md5(implode('',$cache_params));
    }
}
