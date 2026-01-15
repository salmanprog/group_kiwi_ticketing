<?php

namespace App\Models\Hooks\Admin;
use App\Models\{CompanyUser};
use Auth;

class OrganizationHook
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

        $query->select(
                'organizations.*',
                'organization_type.name as organization_name',
                'organization_event_type.name as event_name',
                'organization_users.first_name as contact_first_name',
                'organization_users.last_name as contact_last_name',
                'organization_users.slug as contact_slug'
            )
            ->join('organization_type', 'organization_type.id', '=', 'organizations.organization_type_id')
            ->join('organization_event_type', 'organization_event_type.id', '=', 'organizations.event_type_id')
            ->leftJoin('organization_users', 'organization_users.organization_id', '=', 'organizations.id');

        if(Auth::user()->user_type != 'admin'){
               if(Auth::user()->user_type != 'client'){
                    $getCompany = CompanyUser::getCompany(Auth::user()->id); 
                    $query->where('organizations.company_id', $getCompany->id);   
                }else{
                    $query->where('organizations.client_id', Auth::user()->id);
                }   
        }            

        if( !empty($request['keyword']) ){
            $keyword = $request['keyword'];
            $query->where(function($where) use ($keyword){
                $where->orWhere('organizations.name','like',"$keyword%");
                $where->orWhere('organization_type.name','like',"$keyword%");
                $where->orWhere('organization_event_type.name','like',"$keyword%");
            });
        }

            if (!empty($request['start_date']) && !empty($request['end_date'])) {
                $query->whereBetween('organizations.created_at', [
                    $request['start_date'] . ' 00:00:00',
                    $request['end_date'] . ' 23:59:59'
                ]);
            }

        if( !empty($request['status']) && $request['status'] != 'all' ){
            $status = ( $request['status'] == 'active' ) ? 1 : 0;
            $query->where('organizations.status', $status);
        }


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
        $getCompany = CompanyUser::getCompany(Auth::user()->id); 
        $postdata['company_id'] = $getCompany->id;
        $postdata['created_by'] = Auth::user()->id;
        $postdata['slug'] = uniqid() . time();
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
        $postData['updated_by'] = Auth::user()->id;
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
        $cache_params = $request->except(['user','api_token']);
        return 'product_' . md5(implode('',$cache_params));
    }
}
