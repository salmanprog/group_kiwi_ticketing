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
use App\Models\{CompanyAdmin,Company};

class CompanyController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('Company');
        $this->__request    = $request;
        $this->__data['page_title'] = 'Company Management';
        $this->__indexView  = 'company.index';
        $this->__createView = 'company.add';
        $this->__editView   = 'company.edit';
        $this->__detailView   = 'company.detail';
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
                        'regex:/^(\+1|1)?[-.\s]?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/'
                    ],
                    'password'         => [
                        'required',
                        'regex:/^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8,150}$/'
                    ],
                    'confirm_password' => 'required|same:password',
                    'company_name'            => 'required|min:2|max:50',
                    'company_image_url'       => 'image',
                    'company_address'         => 'required|min:2|max:250',
                    'company_mobile_no'       => 'required|regex:/^(\+1|1)?[-.\s]?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/|unique:company,mobile_no',
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
        $options  = '<a href="'. route('company-management.edit',['company_management' => $record->slug]) .'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
        $options  .= '<a href="'. route('company-management.show',['company_management' => $record->slug]) .'" title="Edit" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';
        return [
            $record->image_url ? '<img src="'. config("constants.storage_url") . $record->image_url .'" alt="Company Logo" class="company-logo" loading="lazy" style="width: 50px; height: 50px; object-fit: cover;">' : 'No logo available',
            $record->admin_name,
            $record->admin_email,
            $record->name,
            $record->mobile_no,
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
        $this->__success_store_message   = 'Company created successfully';
    }

    /**
     * This function is used for before the edit view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     * @param string $slug
     */
    public function beforeRenderEditView($slug)
    {
        $this->__data['getCmsRole'] = UserGroup::getCmsRole();
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
        $company = Company::with('companyAdmin.user')->where('slug', $slug)->first();
        $this->__data['company'] = $company;
        return $this->__cbAdminView($this->__detailView,$this->__data);

    }

   
}
