<?php

namespace App\Http\Controllers\Portal;

use App\Models\ActivityLog;
use App\Models\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\{OrganizationUser, CompanyUser, Client, Product, Estimate, User, Invoice, InstallmentPlan};
use Auth;
use Illuminate\Support\Facades\Crypt;
use DB;
class InvoiceController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('Invoice');
        $this->__request = $request;
        $this->__data['page_title'] = 'Invoice';
        $this->__indexView = 'invoice.index';
        $this->__createView = 'invoice.add';
        $this->__editView = 'invoice.edit';
        $this->__detailView = 'invoice.detail';
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
        $options = '<a href="' . route('invoice.show', ['invoice' => $record->slug]) . '" title="Edit" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';
        $status = '';

        switch ($record->status) {
            case 'unpaid':
                $status = '<span class="btn btn-xs btn-warning">Unpaid</span>';
                break;
            case 'paid':
                $status = '<span class="btn btn-xs btn-success">Paid</span>';
                break;
            case 'partial':
                $status = '<span class="btn btn-xs btn-info">Partial</span>';
                break;
            case 'cancelled':
                $status = '<span class="btn btn-xs btn-danger">Cancelled</span>';
                break;
        }

        return [
            // $record->s,
            ucfirst($record->slug),
            $record->issue_date,
            $status,
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
        $company = CompanyUser::getCompany(Auth::user()->id);
        $this->__data['clients'] = Client::where('company_id', $company->id)->get();
        $this->__data['products'] = Product::where('company_id', $company->id)->get();


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
        $this->__data['clients'] = Client::where('company_id', $company->id)->get();
        $this->__data['products'] = Product::where('company_id', $company->id)->get();

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
        $record = Invoice::with('invoiceItems', 'invoiceTax', 'invoiceDiscount', 'company', 'estimate.organization', 'client')->where('slug', $slug)->first();
        $this->__data['invoice'] = $record;
        return $this->__cbAdminView($this->__detailView, $this->__data);
    }


    public function convertToInstallment($invoiceId)
    {
        // Fetch invoice 
        $invoice = \App\Models\Invoice::findOrFail($invoiceId);
        $invoice->update(['is_installment' => true]);
        $getInstallment = \App\Models\InstallmentPlan::where('invoice_id', $invoiceId)->first();
        // Check for existing installment plan
        if ($getInstallment) {
            return redirect()->back()->with('error', 'Installment plan already exists for this invoice.');
        }
        $estimate = \App\Models\Estimate::where('id', $invoice->estimate_id)->first();
        $totalAmount = $invoice->total;

        // Define percentages (40%, 30%, 30%)
        $percentages = [40, 30, 30];

        // Parse dates
        $eventDate = \Carbon\Carbon::parse($estimate->event_date)->startOfDay();
        $today = \Carbon\Carbon::today();

        // If event date is today or in the past, don't create installments
        if ($eventDate->lte($today)) {
            return redirect()->back()->with('error', 'Cannot create installments: Event date must be in the future');
        }

        // Calculate days until event
        $daysUntilEvent = $today->diffInDays($eventDate);

        // Determine installment count based on days until event
        $installmentCount = min(count($percentages), $daysUntilEvent);

        // If not enough days for installments, adjust the first installment date
        $firstInstallmentDate = $today->copy();
        if ($daysUntilEvent < count($percentages)) {
            // If less days than installments, start from (event date - number of installments)
            $firstInstallmentDate = $eventDate->copy()->subDays($installmentCount - 1);
            // Ensure we don't go before today
            if ($firstInstallmentDate->lt($today)) {
                $firstInstallmentDate = $today->copy();
                // Recalculate installments based on actual available days
                $installmentCount = $firstInstallmentDate->diffInDays($eventDate) + 1;
            }
        }

        // Adjust percentages array if needed
        $percentages = array_slice($percentages, 0, $installmentCount);
        $totalPercentage = array_sum($percentages);

        // Normalize percentages to ensure they sum to 100%
        if ($totalPercentage != 100) {
            $percentages = array_map(function ($p) use ($totalPercentage) {
                return round(($p / $totalPercentage) * 100);
            }, $percentages);
            // Fix any rounding errors on the last item
            $percentages[count($percentages) - 1] += 100 - array_sum($percentages);
        }

        DB::beginTransaction();
        try {
            // Create installment plan
            $plan = \App\Models\InstallmentPlan::create([
                'invoice_id' => $invoice->id,
                'total_amount' => $totalAmount,
                'installment_count' => $installmentCount,
                'start_date' => $firstInstallmentDate,
                'estimate_id' => $invoice->estimate_id
            ]);

            // Create payments based on percentages
            foreach ($percentages as $index => $percent) {
                // Calculate due date - spread installments evenly between first installment date and event date
                $daysToAdd = $index;
                if ($installmentCount > 1) {
                    $daysToAdd = floor(($daysUntilEvent - 1) * ($index / ($installmentCount - 1)));
                }
                $dueDate = $firstInstallmentDate->copy()->addDays($daysToAdd);

                // Ensure we don't go past the event date
                if ($dueDate > $eventDate) {
                    $dueDate = $eventDate->copy();
                }

                $amount = round(($percent / 100) * $totalAmount, 2);

                \App\Models\InstallmentPayment::create([
                    'installment_plan_id' => $plan->id,
                    'installment_number' => $index + 1,
                    'estimate_id' => $invoice->estimate_id,
                    'due_date' => $dueDate,
                    'amount' => $amount,
                    'is_paid' => false,
                ]);
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Installment plan created with 40/30/30% breakdown.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create installment plan: ' . $e->getMessage());
        }
    }

    public function updateInvoiceStatus(Request $request)
    {
        $invoice = Invoice::find($request->invoice_id);
        if ($invoice) {
            $invoice->status = 'paid';
            $invoice->description = $request->notes;
            $invoice->payment_type = $request->payment_type;
            $invoice->paid_amount = $request->total;
            $invoice->save();

        }


        ActivityLog::create([
            'module' => 'contract',
            'module_id' => $invoice->contract_id,
            'description' => Auth::user()->name . ' get a ' . $invoice->payment_type . ' invoice status updated. invoice number is ' . $invoice->invoice_number,
            'user_id' => Auth::id(),
            'old_data' => json_encode($invoice),
            'new_data' => json_encode($invoice),
        ]);


        return redirect()->back()->with('success', 'Invoice status updated successfully.');
    }


    public function updateInstallmentStatus(Request $request)
    {

        InstallmentPayment::where('id', $request->installment_id)
            ->where('installment_plan_id', $request->plane_id)
            ->update([
                'is_paid' => 1,
                'paid_at' => now(),
                'status' => 'paid',
                'payment_type' => $request->payment_type,
                'notes' => $request->notes,
            ]);

        $installmentPayment = InstallmentPayment::where('id', $request->installment_id)
            ->where('installment_plan_id', $request->plane_id)
            ->firstOrFail();

        $installmentPayment->update([
            'is_paid' => 1,
            'paid_at' => now(),
            'status' => 'paid',
        ]);

        $installmentPlan = InstallmentPlan::findOrFail($request->plane_id);
        $totalPayments = InstallmentPayment::where('installment_plan_id', $request->plane_id)
            ->where('is_paid', 1)
            ->count();
        // Check if plan is fully paid
        if ($installmentPlan->installment_count == $totalPayments) {
            $installmentPlan->update([
                'status' => 'completed',
            ]);
        }

        $invoice = Invoice::where('id', $request->invoice_id)->first();
        $invoice->paid_amount += $installmentPayment->amount;
        if ($invoice) {
            if ($totalPayments == '1') {
                $invoice->status = 'partial';
            } elseif ($totalPayments == '2') {
                $invoice->status = 'partial';
            } else {
                $invoice->status = 'paid';
            }
            $invoice->save();
        }


        ActivityLog::create([
            'module' => 'contract',
            'module_id' => $invoice->contract_id,
            'description' => Auth::user()->name . ' get a ' . $invoice->payment_type . ' invoice status updated. invoice number is ' . $invoice->invoice_number,
            'user_id' => Auth::id(),
            'old_data' => json_encode($invoice),
            'new_data' => json_encode($invoice),
        ]);


        return redirect()->back()->with('success', 'Invoice status updated successfully.');
    }

}
