<?php

namespace App\Models\Hooks\Admin;

use Carbon\Carbon;
use App\Models\{Organization, CompanyUser, User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientHook
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
        $query->select('organization_users.*', 'organizations.name as organization_name')
            ->join('organizations', 'organizations.id', 'organization_users.organization_id');

        if (Auth::user()->user_type != 'admin') {
            $getCompany = CompanyUser::getCompany(Auth::user()->id);
            //$query->where('organizations.company_id', $getCompany->id);
            $query->where('organizations.auth_code', Auth::user()->auth_code);
        }

        if (!empty($request['keyword'])) {
            $keyword = $request['keyword'];
            $query->where(function ($where) use ($keyword) {
                $where->orWhere('organization_users.first_name', 'like', "$keyword%");
                $where->orWhere('organization_users.last_name', 'like', "$keyword%");
                $where->orWhere('organization_users.email', 'like', "$keyword%");
                $where->orWhere('organization_users.mobile_no', 'like', "$keyword%");
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
    public function hook_before_add($request, &$postdata)
    {
        $clientExist = User::where('email', $postdata['email'])->first();
        $getCompany = CompanyUser::getCompany(Auth::user()->id);

        if ($clientExist == null) {
            $username = User::generateUniqueUserName($postdata['first_name'] . ' ' . $postdata['last_name']);
            User::create([
                'name' => $postdata['first_name'] . ' ' . $postdata['last_name'],
                'slug' => $username,
                'username' => $username,
                'auth_code' => Auth::user()->auth_code,
                'user_group_id' => 5,
                'user_type' => 'client',
                'email' => $postdata['email'],
            ]);
        }

        $postdata['client_id'] = User::where('email', $postdata['email'])->value('id');
        $postdata['company_id'] = $getCompany->id;
        $postdata['created_by'] = Auth::user()->id;
        $postdata['auth_code'] = Auth::user()->auth_code;
        $postdata['slug'] = uniqid() . time();
        // Organization::where('id', $postdata['organization_id'])->update([
        //     'client_id' => $postdata['client_id']
        // ]);
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
        return 'faq_' . md5(implode('', $cache_params));
    }
}
