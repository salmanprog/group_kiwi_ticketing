<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{CompanyUser, OrganizationType, Event, EventHistoryType,Organization,Client,AccountActivityLog};
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
                    'contact' => 'required',
                    'department' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'country' => 'required',
                    'zip' => 'required',
                    'address_one' => 'required',
                    // 'email' => 'required',
                    // 'phone' => 'required',
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
            $options .= '<a href="' . route('organization.show', ['organization' => $record->slug]) . '" title="View" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';
            //$options .= '<a title="Delete" class="btn btn-xs btn-danger _delete_record" data-slug="' . $record->slug . '"><i class="fa fa-trash"></i></a>';
            
            return [
                '<a href="' . route('organization.show', ['organization' => $record->slug]) . '" 
                    title="View" class="btn btn-xs btn-info">'
                    . e($record->name)  . // escape the name to prevent XSS
                '
                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    style="margin-right:4px; vertical-align:middle;">
                    <path d="M15 3h6v6"></path>
                    <path d="M10 14 21 3"></path>
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                </svg>
                 </a>',
                '<a href="' 
                . (trim(($record->contact_first_name ?? '') . ' ' . ($record->contact_last_name ?? '')) !== '' 
                    ? route('client-management.show', ['client_management' => $record->contact_slug]) 
                    : '#') 
                . '" class="btn btn-xs btn-info">'
                . e(trim(($record->contact_first_name ?? '') . ' ' . ($record->contact_last_name ?? '')) ?: 'N/A')
                . '</a>',

                // $record->email,
                // $record->phone,
                $record->event_date,
                $record->follow_up_date,
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
        // $this->__data['organization_types'] = OrganizationType::whereIn('created_by', [1])->where('auth_code', Auth::user()->auth_code)->where('status', 1)->get();
        $this->__data['organization_types'] = OrganizationType::where(function($query) {
                                                $query->whereIn('created_by', [1])
                                                    ->orWhere('auth_code', Auth::user()->auth_code);
                                            })
                                            ->where('status', 1)
                                            ->get();
        $this->__data['organization_events'] = Event::where(function($query) {
                                                    $query->whereIn('created_by', [1])
                                                            ->orWhere('auth_code', Auth::user()->auth_code);
                                                })
                                                ->where('status', 1)
                                                ->get();
        $this->__data['organization_history_events'] = EventHistoryType::whereIn('created_by', [1,$company->id])->where('status', 1)->get();

         
    }

    /**
     * This function is called before a model load
     */
    public function beforeStoreLoadModel()
    {
        $this->__success_store_message = 'Account created successfully';
    }

    /**
     * This function is used for before the edit view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     * @param string @slug
     */
    public function beforeRenderEditView($slug)
    {
        $company = CompanyUser::getCompany(Auth::user()->id);
        $this->__data['organization_types'] = OrganizationType::whereIn('created_by', [1,$company->id])->where('status', 1)->get();
        $this->__data['organization_events'] = Event::whereIn('created_by', [1,$company->id])->where('status', 1)->get();
         $this->__data['organization_history_events'] = EventHistoryType::whereIn('created_by', [1,$company->id])->where('status', 1)->get();
         $this->__data['organization_contacts'] = Client::select('organization_users.*')->join('organizations', 'organizations.id', '=', 'organization_users.organization_id')->where('organizations.slug', $slug)->get();
    
         $record = Organization::where('slug', $slug)->first();
         $this->__data['activityLog'] = AccountActivityLog::with('createdBy')->where('module','organizations')->where('module_id',$record->id)->get();
        }

    /**
     * This function is called before a model load
     */
    public function beforeUpdateLoadModel()
    {
        $this->__success_update_message = 'Acount updated successfully';

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

        $record = Organization::with([
            'organizationType',
            'eventHistory',
            'eventType',
            'createdBy',
            'updatedBy',
            'contract.client',
            'estimate.estimateinvoices',
        ])
        ->where('slug', $slug)
        ->firstOrFail();

        // Flatten all invoices from estimate
        $invoices = $record->estimate
            ->flatMap(fn($e) => $e->estimateinvoices ?? collect())
            ->values();

        $this->__data['record'] = $record;
        $this->__data['company'] = $company;
        $this->__data['invoices'] = $invoices;
        $this->__data['organization_contacts'] = Client::select('organization_users.*')->join('organizations', 'organizations.id', '=', 'organization_users.organization_id')->where('organizations.slug', $slug)->get();

        $this->__data['activityLog'] = AccountActivityLog::with('createdBy')->where('module','organizations')->where('module_id',$record->id)->get();


        return $this->__cbAdminView($this->__detailView, $this->__data);
    }
}
