<?php

namespace App\Models\Hooks\Admin;

use Illuminate\Support\Facades\Hash;
use App\Models\{Company,CompanyUser};
use Carbon\Carbon;
class CompanyAdminHook
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
    public function hook_query_index(&$query,$request, $slug=NULL)
    {
        $query->select([
            'users.*',
            'ug.title AS user_group',
            'com.name as company_name',
            'com.mobile_no as company_mobile_no',
        ]);
        $query->join('user_groups AS ug', 'ug.id', '=', 'users.user_group_id');
        $query->join('company_users', 'company_users.user_id', '=', 'users.id');
        $query->join('company AS com', 'com.id', '=', 'company_users.company_id');
        $query->where('users.user_group_id', '=', 2);
        $query->where('users.user_type', '=', 'company');

        if( !empty($request['keyword']) ){
            $keyword = $request['keyword'];
            $query->where(function($where) use ($keyword){
                $where->orWhere('users.name','like',"$keyword%");
                $where->orWhere('users.email','like',"$keyword%");
                $where->orWhere('com.mobile_no','like',"$keyword%");
                $where->orWhere('com.name','like',"$keyword%");
                $where->orWhere('users.created_at','like',"$keyword%");
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
        $postdata['user_type'] = 'company';
        $postdata['username']  = $this->_model::generateUniqueUserName($postdata['name']);
        $postdata['slug']      = $postdata['username'];
        $postdata['password']  = Hash::make($postdata['password']);
        $postdata['user_group_id'] = 2;
        if( !empty($postdata['image_url']) ){
            $postdata['image_url'] = uploadMedia('users',$postdata['image_url'],'50X50');
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
        
        $company = new Company();
        $company->name = $request->company_name;
        $company->email = $request->company_email;
        $company->mobile_no = $request->company_mobile_no;
        $company->address = $request->company_address;
        $company->description = $request->company_description;
        $company->website = $request->company_website;
        if( !empty($request['company_image_url']) ){
            $company->image_url = uploadMedia('company',$request['company_image_url'],'50X50');
        }
        $company->save(); 

        CompanyUser::create([
            'company_id' => $company->id,
            'user_id' => $record->id,
            'user_type' => 'Admin',
            'created_at' => Carbon::now()
        ]);

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
        $postData['status'] = $request['status'];
        if( in_array($request['status'], [0,1]) ){
            $company = Company::where('user_id', $this->_model::where('slug', $slug)->value('id'))->first();
            $company->status = $request['status'];
            $company->save();
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
        $cache_params = $request->except(['user','api_token']);
        return 'UserAdmin_' . md5(implode('',$cache_params));
    }
}
