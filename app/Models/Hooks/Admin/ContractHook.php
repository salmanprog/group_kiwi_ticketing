<?php

namespace App\Models\Hooks\Admin;

use App\Models\{CompanyUser, Estimate, EstimateItem, Client};
use Auth;
use DB;

class ContractHook
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
    public function hook_query_index(&$query, $request, $slug = NULL)
    {
        //Your code here
        $getCompany = CompanyUser::getCompany(Auth::user()->id);
        $query->select('contracts.*', 'company.name as company_name');
        $query->join('company', 'company.id', '=', 'contracts.company_id');
        if (Auth::user()->user_type == 'client') {
            $query->where('contracts.client_id', Auth::user()->id);
        } else {
            $query->where('contracts.auth_code', Auth::user()->auth_code);
        }

          if( !empty($request['keyword']) ){
            $keyword = $request['keyword'];
            $query->where(function($where) use ($keyword){
                $where->orWhere('contracts.contract_number','like',"$keyword%");
                $where->orWhere('company.name','like',"$keyword%");
            });
        }

        if (!empty($request['start_date']) && !empty($request['end_date'])) {
                $query->whereBetween('contracts.created_at', [
                    $request['start_date'] . ' 00:00:00',
                    $request['end_date'] . ' 23:59:59'
                ]);
            }

        if( !empty($request['status']) && $request['status'] != 'all' ){
            $status = $request['status'];
            $query->where('contracts.is_accept', $status);
        }
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before add data is execute
    | ----------------------------------------------------------------------
    | @arr
    |
    */
    public function hook_before_add($request, &$postdata)
    {
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after add public static function called
    | ----------------------------------------------------------------------
    | @record
    |
    */
    public function hook_after_add($request, $record)
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
      
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after edit public static function called
    | ----------------------------------------------------------------------
    | @request  = Http request object
    | @$slug    = $slug
    |
    */
    public function hook_after_edit($request, $slug)
    {
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
    public function hook_before_delete($request, $slug)
    {
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
    public function hook_after_delete($request, $records)
    {
        //Your code here
    }

    public function create_cache_signature($request)
    {
        $cache_params = $request->except(['user', 'api_token']);
    }
}
