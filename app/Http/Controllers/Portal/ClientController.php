<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Organization, CompanyUser, OrganizationUser,Client,ContactActivityLog};
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
        //$this->__data['organizations'] = Organization::where('status', 1)->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->where('client_id', 0)->get();
        $this->__data['organizations'] = Organization::where('status', 1)->where('auth_code', Auth::user()->auth_code)->get();
    }

    /**
     * This function is called before a model load
     */
    public function beforeStoreLoadModel()
    {

        $checkUserAlreadyExistOrganization = Client::where('organization_id', $this->__request->organization_id)->where('email', $this->__request->email)->first();
        if ($checkUserAlreadyExistOrganization) {
            return redirect()->back()->with('error', 'User already exist');
        }        
        $this->__success_store_message = 'Contact created successfully';
    }

    /**
     * This function is used for before the edit view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     * @param string @slug
     */
    public function beforeRenderEditView($slug)
    {
        $this->__data['organizations'] = Organization::where('status', 1)->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->get();
        $record = Client::where('slug', $slug)->first();
        $activityLog = ContactActivityLog::with('createdBy')->where('client_id',$record->client_id)->get();
        $this->__data['activityLog'] = $activityLog;
    }

    /**
     * This function is called before a model load
     */
    public function beforeUpdateLoadModel()
    {
        $this->__success_update_message = 'Contact updated successfully';

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
        $company = CompanyUser::getCompany(Auth::user()->id);
        
        // Load client with estimates and invoices
        $record = Client::with('estimates.estimateinvoices')
            ->where('slug', $slug)
            ->firstOrFail();
        
        $organizations =  Organization::with([
        'organizationType',
        'eventHistory',
        'eventType',
        'createdBy',
        'updatedBy',
        'contract.client',
        'estimate.estimateinvoices', // 👈 use your new relation name
        ])->where('status', 1)->where('id', $record->organization_id)->first();

        $activityLog = ContactActivityLog::with('createdBy')->where('client_id',$record->client_id)->where('organization_id',$record->organization_id)->get();
            // dd($activityLog);
        // Flatten invoices from all estimates
          $invoices = $organizations->estimate
            ->flatMap(fn($e) => $e->estimateinvoices ?? collect())
            ->values();
        $this->__data['record'] = $record;
        $this->__data['company'] = $company;
        $this->__data['invoices'] = $invoices;
        $this->__data['organizations'] = $organizations;
        $this->__data['activityLog'] = $activityLog;

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
