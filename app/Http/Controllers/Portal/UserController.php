<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('User');
        $this->__request    = $request;
        $this->__data['page_title'] = 'Users';
        $this->__indexView  = 'users.index';
        $this->__createView = 'users.add';
        $this->__editView   = 'users.edit';
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
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'attribute' => 'required',
                ]);
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method' => 'required|in:PUT',
                    'status'  => 'required|in:1,0',
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
        $options  = '<a href="'. route('manager-management.edit',['app_user' => $record->slug ]) .'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
        return [
            $record->name,
            $record->email,
            $record->mobile_no,
            $record->is_email_verify == 1 ? '<span class="btn btn-xs btn-success">Verified</span>' : '<span class="btn btn-xs btn-danger">not verified</span>',
            $record->is_mobile_verify == 1 ? '<span class="btn btn-xs btn-success">Verified</span>' : '<span class="btn btn-xs btn-danger">not verified</span>',
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
    public function beforeDeleteLoadModel()
    {

    }

    public function noPermission(Request $request)
    {
        // Optional: you can get the flash message if set
        $errorMessage = session('error') ?? 'You don’t have the right permission to access this page.';

        return view('portal.users.nopermission', compact('errorMessage'));
    }

     public function checkPermission(Request $request)
    {
        return view('permissioncheck');
    }
}
