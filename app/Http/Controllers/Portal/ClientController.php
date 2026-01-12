<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Organization, CompanyUser, OrganizationUser,Client};
use Auth;

class ClientController extends CRUDCrontroller
{

    public function __construct(Request $request)
    {
        parent::__construct('Client');
        $this->__request = $request;
        $this->__data['page_title'] = 'Organization client';
        $this->__indexView = 'client.index';
        $this->__createView = 'client.add';
        $this->__editView = 'client.edit';
        $this->__detailView = 'client.detail';
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
            'organization_id.required' => 'Please select organization',
        ];
        switch ($action) {
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'first_name' => 'required|min:2|max:50',
                    'last_name' => 'required|min:2|max:50',
                    'email' => 'required|email|max:100',
                    //'mobile_no' => 'required',
                    //'position' => 'required',
                    'organization_id' => 'required',
                ], $custom_messages);

                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method' => 'required|in:PUT',
                    'status' => 'required|in:0,1',
                ]);
                break;
        }
        return $validator;
    }

    /**
     * This function is used for before the index view render
     * data pass on view eg: $this->__data['title'] = 'Titlse';
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

        $options = '<a href="' . route('client-management.edit', ['client_management' => $record->slug]) . '" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
        $options .= '<a href="' . route('client-management.show', ['client_management' => $record->slug]) . '" title="Edit" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';
        return [
            $record->organization_name,
            $record->first_name,
            $record->email,
            $record->mobile_no,
            // date(config("constants.ADMIN_DATE_FORMAT") , strtotime($record->created_at)),
            $options
        ];
    }

    /**
     * This function is used for before the create view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderCreateView()
    {
        $this->__data['organizations'] = Organization::where('status', 1)->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->where('client_id', 0)->get();
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
        $this->__data['organizations'] = Organization::where('status', 1)->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->get();
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
         $this->__data['organizations'] = Organization::where('status', 1)->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->get();
        $record = Client::with('organization')->where('slug', $slug)
            ->firstOrFail();
        $this->__data['record'] = $record;

        return $this->__cbAdminView($this->__detailView, $this->__data);
    }

    public function fetch($id)
    {
        $organization = Organization::find($id);

        if (!$organization) {
            return response()->json(['status' => false]);
        }

        return response()->json([
            'status' => true,
            'data' => $organization
        ]);
    }
}
