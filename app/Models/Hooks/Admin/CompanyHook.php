<?php

namespace App\Models\Hooks\Admin;

use Illuminate\Support\Facades\Hash;
use App\Models\{Company, CompanyUser, CompanyAdmin};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class CompanyHook
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
        $query->select([
            'company.*',
            'users.name as admin_name',
            'users.email as admin_email',
            'users.mobile_no as admin_mobile_no',
            'users.image_url as admin_image_url',
        ])
            ->join('company_users', 'company_users.company_id', '=', 'company.id')
            ->join('users', 'users.id', '=', 'company_users.user_id')
            ->where('users.user_group_id', 2)
            ->where('company_users.user_type', 'admin');

        if (!empty($request['keyword'])) {
            $keyword = $request['keyword'];
            $query->where(function ($where) use ($keyword) {
                $where->orWhere('users.name', 'like', "$keyword%");
                $where->orWhere('users.email', 'like', "$keyword%");
                $where->orWhere('company.mobile_no', 'like', "$keyword%");
                $where->orWhere('company.name', 'like', "$keyword%");
                $where->orWhere('company.created_at', 'like', "$keyword%");
            });
        }

        if (!empty($request['start_date']) && !empty($request['end_date'])) {
            $query->whereBetween('users.created_at', [
                $request['start_date'] . ' 00:00:00',
                $request['end_date'] . ' 23:59:59'
            ]);
        }

        if (!empty($request['status']) && $request['status'] != 'all') {
            $status = ($request['status'] == 'active') ? 1 : 0;
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
        $postdata['name'] = $postdata['company_name'];
        $postdata['slug'] = $this->_model::generateUniqueSlug(uniqid(uniqid()));
        $postdata['email'] = $postdata['company_email'];
        $postdata['mobile_no'] = $postdata['company_mobile_no'];
        $postdata['address'] = $postdata['company_address'];
        $postdata['description'] = $postdata['company_description'];
        $postdata['website'] = $postdata['company_website'];
        $postdata['email'] = $postdata['email'];
        if (!empty($postdata['company_image_url'])) {
            $postdata['image_url'] = uploadMedia('company', $postdata['company_image_url'], '50X50');
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
        $username = CompanyAdmin::generateUniqueUserName($request->name);
        $companyAdmin = new CompanyAdmin();
        $companyAdmin->user_group_id = 2;
        $companyAdmin->user_type = 'company';
        $companyAdmin->slug = $username;
        $companyAdmin->username = $username;
        $companyAdmin->name = $request->name;
        $companyAdmin->email = $request->email;
        $companyAdmin->mobile_no = $request->mobile_no;
        $companyAdmin->password = Hash::make($request->password);
        if (!empty($request['image_url'])) {
            $companyAdmin->image_url = uploadMedia('users', $request['image_url'], '50X50');
        }
        $companyAdmin->status = 1;
        $companyAdmin->save();

        CompanyUser::create([
            'company_id' => $record->id,
            'user_id' => $companyAdmin->id,
            'user_type' => 'Admin',
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now()
        ]);

        //organization type

        $organizationTypes = [
            'Church Cristian',
            'Church Other',
            'Company',
            'Individual',
            'Non Profit',
            'Other',
            'Elementary School',
            'High School',
            'Middle School',
            'School Other',
            'School Private',
            'Vendor',
            'Youth Girl Scout',
            'Youth Other',
            'Youth Recreation',
            'Youth Sports',
        ];

        foreach ($organizationTypes as $type) {
            \DB::table('organization_type')->insert([
                'name' => $type,
                'company_id' => $record->id,
                'slug' => Str::slug($type) . $record->id,
            ]);
        }


        $data = [
            'username' => $companyAdmin->name,
            'message' => "Your account has been successfully created. Here are your login details:",
            'email' => $companyAdmin->email,
            'password' => $request->password
        ];
        $subject = "Company Register Confirmation";
        Mail::send('email.company_register', $data, function ($message) use ($companyAdmin, $subject) {
            $message->to($companyAdmin->email)->subject($subject);
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
    public function hook_before_edit($request, $slug, &$postData)
    {
        $postData['name'] = $request['company_name'];
        $postData['mobile_no'] = $request['company_mobile_no'];
        $postData['address'] = $request['company_address'];
        $postData['description'] = $request['company_description'];
        $postData['website'] = $request['company_website'];
        if (!empty($request['company_image_url'])) {
            $postData['image_url'] = uploadMedia('company', $request['company_image_url'], '50X50');
        }
        $postData['status'] = $request['status'];

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
        return 'UserAdmin_' . md5(implode('', $cache_params));
    }
}
