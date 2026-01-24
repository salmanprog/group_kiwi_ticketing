<?php

namespace App\Http\Controllers\Portal;

use App\Models\CmsRole;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\CompanyAdmin;

class CompanyAdminController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('CompanyAdmin');
        $this->__request    = $request;
        $this->__data['page_title'] = 'Company Management';
        $this->__indexView  = 'company-admin.index';
        $this->__createView = 'company-admin.add';
        $this->__editView   = 'company-admin.edit';
        $this->__detailView   = 'company-admin.detail';
    }

    /**
     * This function is used for validate data
     * @param string $action
     * @param string $slug
     * @return array|\Illuminate\Contracts\Validation\Validator
     */
    public function validation(string $action, string $slug=NULL)
    {
        $validator = [];
        $custom_messages = [
            'password.regex' => __('app.password_regex'),
            'mobile_no.unique' => __('app.user_mobile_no_unique'),
            'company_mobile_no.unique' => __('app.company_mobile_no_unique'),
            'company_email.unique' => __('app.company_email_unique'),
            'email.unique' => __('app.user_email_unique'),
        ];
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'name'             => 'required|min:2|max:50',
                    'email'            => 'required|email|max:100|unique:users,email,NULL,deleted_at',
                    'image_url'        => 'required|image',
                    'mobile_no'        => [
                        'required',
                        'unique:users,mobile_no,NULL,deleted_at',
                        'regex:/^(\+?\d{1,3}[-])\d{9,11}$/'
                    ],
                    'password'         => [
                        'required',
                        'regex:/^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8,150}$/'
                    ],
                    'confirm_password' => 'required|same:password',
                    'company_name'            => 'required|min:2|max:50',
                    'company_image_url'       => 'image',
                    'company_address'         => 'required|min:2|max:250',
                    'company_mobile_no'       => 'required|regex:/^(\+?\d{1,3}[-])\d{9,11}$/|unique:company,mobile_no',
                    'company_email'           => [
                        'required',
                        'email',
                        'max:100',
                        'unique:company,email',
                    ],
                    'company_website'         => 'nullable|url|max:100',
                    'company_description'     => 'required|min:2|max:500',
                ],$custom_messages);
                    
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method'   => 'required|in:PUT',
                    'status'    => 'required|in:0,1',
                ]);
                break;
        }
        return $validator;
    }

    /**
     * This function is used for before the index view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderIndexView()
    {

    }

    /**
     * This function is used to add data in datatable
     * @param object $record
     * @return array
     */
    public function dataTableRecords($record)
    {
        $options  = '<a href="'. route('company-management.edit',['company_management' => $record->username]) .'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
        $options  .= '<a href="'. route('company-management.show',['company_management' => $record->slug]) .'" title="Edit" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';
        return [
            $record->name,
            $record->email,
            $record->company_name,
            $record->company_mobile_no,
            $record->status == 1 ? '<span class="btn btn-xs btn-success">Active</span>' : '<span class="btn btn-xs btn-danger">Disabled</span>',
            date(config("constants.ADMIN_DATE_FORMAT") , strtotime($record->created_at)),
            $options
        ];
    }

    /**
     * This function is used for before the create view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderCreateView()
    {
        $this->__data['getCmsRole'] = UserGroup::getCmsRole();
    }

    /**
     * This function is called before a model load
     */
    public function beforeStoreLoadModel()
    {

    }

    /**
     * This function is used for before the edit view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     * @param string $slug
     */
    public function beforeRenderEditView($slug)
    {
        $this->__data['getCmsRole'] = UserGroup::getCmsRole();
        $this->__data['company'] = CompanyAdmin::with('company')->where('slug', $slug)->first();
    }

    /**
     * This function is called before a model load
     */
    public function beforeUpdateLoadModel()
    {

    }

    /**
     * This function is called before a model load
     */
    public function beforeDeleteLoadModel()
    {

    }

    public function show($slug)
    {
        $company = CompanyAdmin::with('company')->where('slug', $slug)->first();
        $this->__data['company'] = $company;
        return $this->__cbAdminView($this->__detailView,$this->__data);

    }


    /**
     * This function is used to render profile view
     * @param Request $request
     * @return view
     */
    public function profile(Request $request)
    {
        if( $request->isMethod('post') )
            return self::_submitProfile($request);

        $data['page_title'] = 'Profile';
        return $this->__cbAdminView('cms_users.profile',$data);
    }

    /**
     * This function is used to submit profile data
     * @param Request $request
     * @return redirect
     */
    private function _submitProfile($request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|min:3|max:50',
            'email'     => [
                'required',
                'email',
                Rule::unique('users')->whereNull('deleted_at')->ignore(currentUser()->id),
            ],
            'mobile_no' => [
                'min:8',
                'max:15',
                Rule::unique('users')->whereNull('deleted_at')->ignore(currentUser()->id),
            ],
            'image_url' => 'image|max:5120', //2mb
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        User::updateProfile($request->all());
        return redirect()->back()->with('success','Profile has been updated successfully');
    }

    /**
     * This function is used to render change password view
     * @param Request $request
     * @return view
     */
    public function changePassword(Request $request)
    {
        if( $request->isMethod('post') )
            return self::_submitChangePassword($request);

        $data['page_title'] = 'Change Password';
        return $this->__cbAdminView('cms_users.change-password',$data);
    }

    /**
     * This function is used to submit change password data
     * @param Request $request
     * @return redirect
     */
    private function _submitChangePassword($request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password'     => 'required|min:6|max:255',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if( !Hash::check($request['current_password'],currentUser()->password) )
            return redirect()->back()->with('error','Invalid current password');

        User::updatePassword(currentUser()->id,$request['new_password']);
        return redirect()->back()->with('success','Password has been updated successfully');
    }

    /**
     * This is used to logout current user
     * @return redirect
     */
    public function logout()
    {
        session()->flush();
        Auth::logout();
        // $login_url = route('admin.login') . '?auth_token=' . config('constants.ADMIN_AUTH_TOKEN');
        $login_url = route('admin.login');
        return redirect($login_url)->with('success','You have logged out successfully');
    }

    public function stripeKey(Request $request)
    {
        if( $request->isMethod('post') )
            return self::_submitStripeKey($request);

        $data['page_title'] = 'Profile';
        return $this->__cbAdminView('cms_users.stripe-key',$data);
    }

    public function termsAndConditions(Request $request)
    {

        $data['page_title'] = 'Terms & Conditions';
        $getCompany = \App\Models\CompanyUser::getCompany(Auth::user()->id);
        $terms_and_conditions = \App\Models\TermsAndCondition::where('company_id', $getCompany->id)->first();

        $data['record'] = $terms_and_conditions;
        return $this->__cbAdminView('cms_users.terms-and-conditions',$data);
    }

    public function updateTermsAndConditions(Request $request)
    {   
        $getCompany = \App\Models\CompanyUser::getCompany(Auth::user()->id);
        $terms_and_conditions = \App\Models\TermsAndCondition::firstOrCreate(
                                ['company_id' => $getCompany->id], [
                                    'user_id' => Auth::user()->id,
                                    'content' => $request->content 
                                ]
                            );
        return redirect()->back()->with('success','Terms & Conditions has been updated successfully');
    }

    /**
     * This function is used to submit profile data
     * @param Request $request
     * @return redirect
     */
    private function _submitStripeKey($request)
    {
        // dd($request->test_publishable_key);
        // $validator = Validator::make($request->all(), [
        //     'stripe_key_status' => 'required|in:live,test',
        //     'test_publishable_key' => 'required_with:stripe_key_status,test|nullable',
        //     'test_secret_key' => 'required_with:stripe_key_status,test|nullable',
        //     'live_publishable_key' => 'required_with:stripe_key_status,live|nullable',
        //     'live_secret_key' => 'required_with:stripe_key_status,live|nullable',
        // ]);

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        User::updateStripeKeys($request->all());
        return redirect()->back()->with('success','Profile has been updated successfully');
    }
}
