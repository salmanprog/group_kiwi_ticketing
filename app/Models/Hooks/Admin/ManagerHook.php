<?php

namespace App\Models\Hooks\Admin;

use Carbon\Carbon;
use App\Models\{Manager, CompanyUser,User};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ManagerHook
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
        $query->select('users.*')->where('users.user_type', 'manager')
            ->join('company_users', 'company_users.user_id', '=', 'users.id')
            ->where('company_users.user_type', 'manager');


        if (Auth::user()->user_type != 'admin') {
            $getCompany = CompanyUser::getCompany(Auth::user()->id);
            $query->where('company_users.company_id', $getCompany->id);
        }



        if (!empty($request['keyword'])) {
            $keyword = $request['keyword'];
            $query->where(function ($where) use ($keyword) {
                $where->orWhere('users.name', 'like', "$keyword%");
                $where->orWhere('users.email', 'like', "$keyword%");
                $where->orWhere('users.mobile_no', 'like', "$keyword%");
                $where->orWhere('users.created_at', 'like', "$keyword%");
            });
        }

        if (!empty($request['start_date']) && !empty($request['end_date'])) {
            $query->whereBetween('users.created_at', [
                $request['start_date'] . ' 00:00:00',
                $request['end_date'] . ' 23:59:59'
            ]);
        }

        if (!empty($request['status']) && $request['status'] != 'all') {
            $status = ($request['status'] == 'active') ? '1' : '0';
            $query->where('users.status', $status);
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
        $username = $this->_model::generateUniqueUserName($postdata['name']);
        $postdata['slug']  = $username;
        $postdata['username'] = $username;
        $postdata['user_group_id'] = 3;
        $postdata['password']  = Hash::make($postdata['password']);
        $postdata['user_type'] = 'manager';
        if (!empty($postdata['image_url'])) {
            $postdata['image_url'] = uploadMedia('manager', $postdata['image_url'], '50X50');
        }
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
        
        $getCompany = CompanyUser::getCompany(Auth::user()->id);
        CompanyUser::create([
            'user_id' => $record->id,
            'company_id' => $getCompany->id,
            'user_type' => 'manager',
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now()
        ]);

        $data = [
            'support_email' => User::where('user_type', 'admin')->first()->email,
            'support_number' => User::where('user_type', 'admin')->first()->mobile_no,
            'company_name' => $getCompany->name,
            'username' => $record->name,
            'message' =>  "Your account has been successfully created. Here are your login details:",
            'email' => $record->email,
            'password' => $request->password
        ];
        $subject = "Manager Register Confirmation";
        Mail::send('email.manager_register', $data, function ($message) use ($record, $subject) {
            $message->to($record->email)->subject($subject);
        });
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
    public function hook_before_edit($request, $slug, &$postData) {}

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
