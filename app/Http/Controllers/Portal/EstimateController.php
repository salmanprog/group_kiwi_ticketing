<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{OrganizationUser, CompanyUser, Client, Product, Estimate, User, Invoice, Contract,EstimateInstallment};
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;


class EstimateController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('Estimate');
        $this->__request = $request;
        $this->__data['page_title'] = 'Estimate';
        $this->__indexView = 'estimate.index';
        $this->__createView = 'estimate.add';
        $this->__editView = 'estimate.edit';
        $this->__detailView = 'estimate.detail';
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
            'client_id.required' => 'Please select client',
        ];
        switch ($action) {
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'client_id' => 'required',
                    'estimate_date' => 'required',
                    'estimate_expiry_date' => 'nullable|date|after:estimate_date',
                ], $custom_messages);

                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method' => 'required|in:PUT',
                    'client_id' => 'required',
                    'estimate_date' => 'required',
                    'estimate_expiry_date' => 'nullable|date|after:estimate_date',
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
        if (Auth::user()->user_type == 'client') {
            $options = '<a href="' . route('estimate.show', ['estimate' => $record->slug]) . '" title="Edit" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';
        } else {
            $options = [
                '<a href="' . route('estimate.show', ['estimate' => $record->slug]) . '" title="View" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>'
            ];

            if ($record->status != 'approved') {
                $options[] = '<a href="' . route('estimate.edit', ['estimate' => $record->slug]) . '" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
            }

            $options = implode(' ', $options);
        }
        $status = '';

        switch ($record->status) {
            case 'draft':
                $status = '<span class="btn btn-xs btn-secondary">Draft</span>';
                break;

            case 'sent':
                $status = (Auth::user()->user_type == 'client') ? '<span class="btn btn-xs btn-info">New</span>' : '<span class="btn btn-xs btn-info">Sent</span>';
                break;
            case 'approved':
                $status = '<span class="btn btn-xs btn-success">Approved</span>';
                break;

            case 'revised':
                $status = '<span class="btn btn-xs btn-warning">Revised</span>';
                break;

            case 'rejected':
                $status = '<span class="btn btn-xs btn-danger">Rejected</span>';
                break;

            default:
                $status = '<span class="btn btn-xs btn-light">Unknown</span>';
                break;
        }

        return [
            $record->organization_name,
            ucfirst($record->slug),
            $record->issue_date,
            $status, // ✅ use HTML label here
            date(config("constants.ADMIN_DATE_FORMAT"), strtotime($record->created_at)),
            $options
        ];
    }

    /**
     * This function is used for before the create view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderCreateView()
    {
        $this->__data['contract_slug'] = (isset($this->__request->contract)) ? decrypt($this->__request->contract) : '';
        $this->__data['client_id'] = (isset($this->__request->contract)) ? Contract::where('slug', decrypt($this->__request->contract))->value('client_id') : '';
        $company = CompanyUser::getCompany(Auth::user()->id);
        $this->__data['clients'] = Client::where('organization_users.company_id', $company->id)
            ->select('organization_users.*', 'organizations.name as organization_name')
            ->join('organizations', 'organizations.id', '=', 'organization_users.organization_id')
            ->where('organizations.status', '1')->where('organizations.deleted_at', null)
            ->get();
        $this->__data['products'] = Product::where('company_id', $company->id)->get();
    }

    /**
     * This function is called before a model load
     */
    public function beforeStoreLoadModel()
    {
        $this->__success_store_message = 'Estimate created successfully';
    }

    /**
     * This function is used for before the edit view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     * @param string @slug
     */
    public function beforeRenderEditView($slug)
    {
        $company = CompanyUser::getCompany(Auth::user()->id);
        $estimate = Estimate::with('items')->where('slug', $slug)->first();
        $this->__data['estimate'] = $estimate;
        $this->__data['clients'] = Client::where('company_id', $company->id)->get();
        $this->__data['products'] = Product::where('company_id', $company->id)->get();
        $this->__data['installments'] = EstimateInstallment::where('estimate_id', $estimate->id)->get();
        $this->__data['default_terms_and_condition'] = \App\Models\TermsAndCondition::where('company_id', $company->id)->first();
        $this->__data['logs'] = DB::table('user_activity_logs')->select('users.name as user_name', 'user_activity_logs.*')
            ->join('users', 'users.id', '=', 'user_activity_logs.user_id')
            ->where('module', 'estimate')->where('module_id', $estimate->id)
            ->orderBy('created_at', 'desc')->get();
    }

    /**
     * This function is called before a model load
     */
    public function beforeUpdateLoadModel()
    {
        if (!empty($this->__request->mail_send) && $this->__request->mail_send == '1') {
            $this->__success_update_message = 'Estimate has been sent successfully';
        } else {
            $this->__success_update_message = 'Estimate updated successfully';
        }
    }

    /**
     * This function is called before a model load
     */
    public function beforeDeleteLoadModel()
    {
    }


    public function show($slug)
    {
        $record = Estimate::with([
            'items',
            'taxes',
            'discounts',
            'company',
            'organization' => function ($query) {
                $query->withTrashed();
            },
            'client',
            'invoices' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            },
            'installments',
        ])
            ->where('slug', $slug)
            ->first();

        $this->__data['estimate'] = $record;
        $this->__data['logs'] = DB::table('user_activity_logs')->select('users.name as user_name', 'user_activity_logs.*')
            ->join('users', 'users.id', '=', 'user_activity_logs.user_id')
            ->where('module', 'estimate')->where('module_id', $record->id)
            ->orderBy('created_at', 'desc')->get();
        return $this->__cbAdminView($this->__detailView, $this->__data);
    }

    public function saveEstimate(Request $request)
    {
        // dd($request->all());
        $getEstimate = Estimate::where('slug', $request->slug)->first();
        $getClientEmail = Client::where('client_id', $getEstimate->client_id)->where('company_id', $getEstimate->company_id)->first();
        $user = User::where('email', $getClientEmail->email)->first();
        $getCompany = CompanyUser::getCompany(Auth::user()->id);
        if ($user) {

            $mail_params['username'] = $getClientEmail->first_name . ' ' . $getClientEmail->last_name;
            $mail_params['link'] = ($user->password == null) ? route('admin.create-password', ['any' => Crypt::encrypt($user->email)]) : env('APP_URL');
            $mail_params['message'] = ($getEstimate->status == 'draft') ? 'You have a new estimate from ' . "$getCompany->name" : 'company review estimate from ' . "$getCompany->name";
            $subject = $getEstimate->status == 'draft' ? "New Draft from " . $getCompany->name : "New Estimate from " . $getCompany->name;
            sendMail(
                $user->email,
                'estimate',
                'New Estimate',
                $mail_params
            );
        }
        // dd($mail_params['link']);

        Estimate::where('slug', $request->slug)->update([
            'status' => 'sent'
        ]);
        return redirect()
            ->back()
            ->with('success', 'Estimate has been save');
    }

    // public function acceptEstimate(Request $request)
    // {

    //     $estimate = Estimate::where('slug', $request->slug)->firstOrFail();
    //     dd($estimate);
    //     $estimate->update(['status' => 'approved']);

    //     $contract = Contract::find($estimate->contract_id);
    //     if (!$contract) {
    //         $contractSlug = Contract::generateUniqueSlug();
    //         $contract = Contract::create([
    //             'slug' => $contractSlug,
    //             'contract_number' => $contractSlug,
    //             'client_id' => $estimate->client_id,
    //             'company_id' => $estimate->company_id,
    //             'organization_id' => $estimate->organization_id,
    //             'status' => 'active',
    //             'event_date' => $estimate->event_date,
    //             'total' => $estimate->total,
    //             'terms' => $estimate->terms,
    //         ]);
    //     } else {
    //         $contract->total += $estimate->total;
    //         $contract->save();
    //     }
    //     $estimate->contract_id = $contract->id;
    //     $estimate->save();


    //     $invoice = Invoice::generateInvoice($request, $estimate, $contract);


    //     \App\Models\ActivityLog::create(
    //         [
    //             'module' => 'estimate',
    //             'module_id' => $estimate->id,
    //             'description' => 'Estimate Approved by ' . Auth::user()->name,
    //             'user_id' => Auth::id(),
    //             'old_data' => json_encode($estimate->toArray()),
    //             'new_data' => json_encode($estimate->toArray()),
    //         ],
    //         [
    //             'module' => 'contract',
    //             'module_id' => $contract->id,
    //             'description' => 'Contract Create by ' . Auth::user()->name,
    //             'user_id' => Auth::id(),
    //             'old_data' => json_encode($contract->toArray()),
    //             'new_data' => json_encode($contract->toArray()),
    //         ]
    //     );



    //     return redirect()
    //         ->route('invoice.show', ['invoice' => $invoice->slug])
    //         ->with('success', 'Invoice Created & Contract Updated.');
    // }

    public function acceptEstimate(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $estimate = Estimate::where('slug', $request->slug)->firstOrFail();
            $oldEstimateData = $estimate->toArray();

            $estimate->update(['status' => 'approved']);

            $contract = Contract::find($estimate->contract_id);

            if (!$contract) {
                $contractSlug = Contract::generateUniqueSlug();
                $contract = new Contract([
                    'slug' => $contractSlug,
                    'contract_number' => $contractSlug,
                    'client_id' => $estimate->client_id,
                    'company_id' => $estimate->company_id,
                    'organization_id' => $estimate->organization_id,
                    'status' => 'active',
                    'event_date' => $estimate->event_date,
                    'total' => $estimate->total,
                    'terms' => $estimate->terms,
                    'is_accept' => 1,
                    'notes' => $estimate->note,
                    'terms_and_condition' => $estimate->terms_and_condition,
                ]);
            } else {
                $contract->total += $estimate->total;
            }

            $contract->save();

            $estimate->update(['contract_id' => $contract->id]);

            $invoice = Invoice::generateInvoice($request, $estimate, $contract); 

            $this->logActivity('estimate', $estimate->id, 'Estimate Approved', $oldEstimateData, $estimate->toArray());
            $this->logActivity('contract', $contract->id, 'Contract Updated/Created', [], $contract->toArray());

            return redirect()
                ->route('invoice.show', ['invoice' => $invoice->slug])
                ->with('success', 'Invoice Created & Contract Updated.');
        });
    }

    protected function logActivity($module, $id, $desc, $old, $new)
    {
        \App\Models\ActivityLog::create([
            'module' => $module,
            'module_id' => $id,
            'description' => $desc . ' by ' . Auth::user()->name,
            'user_id' => Auth::id(),
            'old_data' => json_encode($old),
            'new_data' => json_encode($new),
        ]);
    }

    public function rejectEstimate(Request $request)
    {
        $estimate = Estimate::where('slug', $request->slug)->firstOrFail();
        $estimate->update([
            'status' => 'rejected'
        ]);

        \App\Models\ActivityLog::create([
            'module' => 'estimate',
            'module_id' => $estimate->id,
            'description' => 'Estimate Rejected by ' . Auth::user()->name,
            'user_id' => Auth::id(),
            'old_data' => json_encode($estimate->toArray()),
            'new_data' => json_encode($estimate->toArray()),
        ]);


        return redirect()
            ->back()
            ->with('success', 'The estimate has been rejected');
    }
}
