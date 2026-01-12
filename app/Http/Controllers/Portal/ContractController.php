<?php

namespace App\Http\Controllers\Portal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{Organization, CompanyUser, Contract, Invoice, CreditNote, Estimate};
use Auth;

class ContractController extends CRUDCrontroller
{

    public function __construct(Request $request)
    {
        parent::__construct('Contract');
        $this->__request = $request;
        $this->__data['page_title'] = 'Contract';
        $this->__indexView = 'contract.index';
        // $this->__createView = 'contractss.edit';
        $this->__detailView = 'contract.detail';
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
                    'mobile_no' => 'required',
                    'position' => 'required',
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

        $options = '<a href="' . route('contract.show', ['contract' => $record->slug]) . '" title="View" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';
        return [
            $record->contract_number,
            $record->company_name,
            $record->total,
            ($record->is_accept == 'pending') ? '<span class="btn btn-xs btn-warning">Pending</span>' : (($record->is_accept == 'accepted') ? '<span class="btn btn-xs btn-success">Accepted</span>' : '<span class="btn btn-xs btn-danger">Rejected</span>'),
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
        $this->__data['record'] = Contract::with([
            'organization' => function ($query) {
                $query->withTrashed();
            },
            'company',
            'client',
            'estimates',
            'invoices.installmentPlan.payments',
            'invoices.creditNotes'
        ])
            ->where('slug', $slug)
            ->first();

        // dd($this->__data['record']->company);


        return $this->__cbAdminView($this->__detailView, $this->__data);
    }

    public function acceptContract($slug)
    {
        $record = Contract::where('slug', $slug)->first();
        $record->is_accept = 'accepted';
        $record->save();
        return redirect()->back()->with('success', 'Contract accepted successfully');
    }

    public function rejectContract($slug)
    {
        $record = Contract::where('slug', $slug)->first();
        $record->is_accept = 'rejected';
        $record->save();
        return redirect()->back()->with('success', 'Contract accepted successfully');
    }

    public function updateContract(Request $request, $slug)
    {
        $record = Contract::where('slug', $slug)->first();
        $record->event_date = $request->event_date;
        $record->terms = $request->terms;
        $record->notes = $request->notes;
        $record->save();
        return redirect()->back()->with('success', 'Contract accepted successfully');
    }

    public function eventCalander()
    {

        $this->__data['contracts'] = Contract::with('organization','userestimates','userestimates.items')->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->get();
        $this->__data['estimates'] = Estimate::with('client')->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->get();
        // dd($this->_data['contracts']);
        // $this->__data['organizations'] = Organization::where('status', 1)->where('company_id', CompanyUser::getCompany(Auth::user()->id)->id)->where('client_id','>', 0)->get();
        return $this->__cbAdminView('contract.event-calander', $this->__data);
    }

    public function addCreditNote($slug)
    {

        $invoice = Invoice::where('slug', $slug)->first();
        $contract = Contract::where('id', $invoice->contract_id)->first();
        $estimate = Estimate::where('id', $invoice->estimate_id)->first();
        $creditNoteExists = CreditNote::where('invoice_id', $invoice->id)->where('contract_id', $contract->id)->where('estimate_id', $estimate->id)->first();
        if ($creditNoteExists) {
            return redirect()->back()->with('error', 'Credit note already exists');
        }

        $this->__data['contract'] = Contract::where('id', $invoice->contract_id)->first();
        $this->__data['invoice'] = $invoice;
        return $this->__cbAdminView('contract.credit-note-add', $this->__data);
    }

    public function saveCreditNote(Request $request)
    {
        $invoice = Invoice::where('slug', $request->invoice_slug)->first();
        if (!$invoice) {
            return redirect()->back()->with('error', 'Invalid invoice');
        }

        if ($request->amount > $invoice->paid_amount) {
            return redirect()->back()->with('error', 'Amount can not be greater than total amount');
        }


        $contract = Contract::where('id', $invoice->contract_id)->first();
        $estimate = Estimate::where('id', $invoice->estimate_id)->first();

        $creditNoteExists = CreditNote::where('invoice_id', $invoice->id)->where('contract_id', $contract->id)->where('estimate_id', $estimate->id)->first();

        if ($creditNoteExists) {
            return redirect()->back()->with('error', 'Credit note already exists');
        }

        $creditNote = new CreditNote();
        $creditNote->contract_id = $contract->id;
        $creditNote->invoice_id = $invoice->id;
        $creditNote->estimate_id = $estimate->id;
        $creditNote->amount = $request->amount;
        $creditNote->reason = $request->reason;
        $creditNote->save();

        return redirect()->route('contract.show', $contract->slug)->with('success', 'Credit note added successfully');
    }
}
