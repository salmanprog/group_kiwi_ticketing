<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{CompanyUser, OrganizationType, Event};
use Auth;

class OrganizationController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('Organization');
        $this->__request = $request;
        $this->__data['page_title'] = 'Organization';
        $this->__indexView = 'organization.index';
        $this->__createView = 'organization.add';
        $this->__editView = 'organization.edit';
        $this->__detailView = 'organization.detail';
    }

    /**
     * This function is used for validate data
     * @param string $action
     * @param string $slug
     * @return array|\Illuminate\Contracts\Validation\Validator
     */
    public function validation(string $action, string $slug = NULL)
    {
        $validator = [];
        $custom_messages = [
            'event_type_id.required' => 'Please select event type',
            'organization_type_id.required' => 'Please select organization type',
        ];
        switch ($action) {
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'name' => 'required|min:2|max:50',
                    'organization_type_id' => 'required',
                    'event_type_id' => 'required',
                    'department' => 'required',
                ], $custom_messages);

                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method' => 'required|in:PUT',
                    'name' => 'required|min:2|max:50',
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

        if (Auth::user()->user_type == 'company' || Auth::user()->user_type == 'salesman' || Auth::user()->user_type == 'manager') {
            $options = '<a href="' . route('organization.edit', ['organization' => $record->slug]) . '" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
            $options .= '<a title="Delete" class="btn btn-xs btn-danger _delete_record" data-slug="' . $record->slug . '"><i class="fa fa-trash"></i></a>';
            return [
                $record->name,
                $record->organization_name,
                $record->event_name,
                $record->status == 1 ? '<span class="btn btn-xs btn-success">Active</span>' : '<span class="btn btn-xs btn-danger">Disabled</span>',
                // date(config("constants.ADMIN_DATE_FORMAT") , strtotime($record->created_at)),
                $options
            ];
        } else if (Auth::user()->user_type == 'client' || Auth::user()->user_type == 'admin') {
            return [
                $record->name,
                $record->organization_name,
                $record->event_name,
                $record->status == 1 ? '<span class="btn btn-xs btn-success">Active</span>' : '<span class="btn btn-xs btn-danger">Disabled</span>',
                // date(config("constants.ADMIN_DATE_FORMAT") , strtotime($record->created_at)),
            ];
        }

    }

    /**
     * This function is used for before the create view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderCreateView()
    {

        $company = CompanyUser::getCompany(Auth::user()->id);
        $this->__data['organization_types'] = OrganizationType::where('company_id', $company->id)->where('status', 1)->get();
        $this->__data['organization_events'] = Event::where('company_id', $company->id)->where('status', 1)->get();
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
        $company = CompanyUser::getCompany(Auth::user()->id);
        $this->__data['organization_types'] = OrganizationType::where('company_id', $company->id)->where('status', 1)->get();
        $this->__data['organization_events'] = Event::where('company_id', $company->id)->where('status', 1)->get();
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


    }
}
