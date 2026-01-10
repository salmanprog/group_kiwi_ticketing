<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Salesman};
use Auth;

class SalesmanController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('Salesman');
        $this->__request    = $request;
        $this->__data['page_title'] = 'Salesman Management';
        $this->__indexView  = 'salesman.index';
        $this->__createView = 'salesman.add';
        $this->__editView   = 'salesman.edit';
        $this->__detailView = 'salesman.detail';
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
                    'confirm_password' => 'required|same:password'
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
             $options  = '';

        // $options  = '<a href="'. route('salesman-management.show',['salesman_management' => $record->slug ]) .'" title="Edit" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';
        $options .= '<a href="'. route('salesman-management.edit',['salesman_management' => $record->slug ]) .'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';

        if(Auth::user()->user_type == 'company')
        {
            $options .= '<a title="Delete" class="btn btn-xs btn-danger _delete_record" data-slug="'.$record->slug.'"><i class="fa fa-trash"></i></a>';
        }
        return [
            $record->name,
            $record->email,
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

    }

    /**
     * This function is called before a model load
     */
    public function beforeStoreLoadModel()
    {
        $this->__success_store_message   = 'Salesman created successfully';
    }

    /**
     * This function is used for before the edit view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     * @param string @slug
     */
    public function beforeRenderEditView($slug)
    {

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
    public function beforeRenderDetailView()
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
        $record = Salesman::where('slug', $slug)->first();
        $this->__data['record'] = $record;
        return $this->__cbAdminView($this->__detailView,$this->__data);

    }


}
