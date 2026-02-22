<?php

namespace App\Models\Hooks\Admin;
use App\Models\{CompanyUser};
use Auth;
class ProductHook
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
        $getCompany = CompanyUser::getCompany(Auth::user()->id);

        // $query->select('company_products.*','company_product_category.name as category_name')
        // ->join('company_product_category','company_product_category.id','company_products.company_product_category_id');

        $query->select('company_products.*');
        // ->join('company_product_category','company_product_category.id','company_products.company_product_category_id');
        //$query->where('company_products.company_id', $getCompany->id);
        $query->where('company_products.auth_code', Auth::user()->auth_code);
            
        if( !empty($request['keyword']) ){
            $keyword = $request['keyword'];
            $query->where(function($where) use ($keyword){
                $where->orWhere('company_products.name','like',"$keyword%");
                $where->orWhere('company_products.price','like',"$keyword%");
                $where->orWhere('company_products.unit','like',"$keyword%");
            });
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
        // dd($request->all());
        $postdata['slug'] = uniqid() . time();
        $getCompany = CompanyUser::getCompany(Auth::user()->id); 
        $postdata['company_id'] = $getCompany->id;
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
        if($request->notes_toggle != 'on'){
            $postData['description'] = null;            
        }

        $postData['tax'] = ($request->tax??0);
        $postData['gratuity'] = ($request->gratuity??0);
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
