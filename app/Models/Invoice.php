<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

// use App

class Invoice extends Model
{
    use SoftDeletes, CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_invoices';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'slug',
        'created_by',
        'estimate_number',
        'company_id',
        'organization_id',
        'issue_date',
        'valid_until',
        'note',
        'terms',
        'status',
        'sub_total',
        'total',
        'tax_total',
        'discount_total',
        'is_installment',
        'is_open',
        'created_at',
        'updated_at',
        'deleted_at',
        'paid_amount',
        'payment_type',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * It is used to enable or disable DB cache record
     * @var bool
     */
    protected $__is_cache_record = false;

    /**
     * @var
     */
    protected $__cache_signature;

    /**
     * @var string
     */
    protected $__cache_expire_time = 1; //days

    public static function generateInvoiceNumber()
    {
        $slug = 'Inv-' . rand(111, 999);
        $slug = strtolower($slug);
        $count = 1;
        while (Invoice::where('slug', $slug . '-' . $count)->exists()) {
            $count++;
        }
        return $slug . '-' . $count;
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function invoiceTax()
    {
        return $this->hasMany(InvoiceTax::class, 'invoice_id', 'id');
    }

    public function invoiceDiscount()
    {
        return $this->hasMany(InvoiceDiscount::class, 'invoice_id', 'id');
    }

    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    public function estimate()
    {
        return $this->hasOne(Estimate::class, 'id', 'estimate_id');
    }


    public function client()
    {
        return $this->hasOne(Client::class, 'client_id', 'client_id');
    }

    public function installmentPlan()
    {
        return $this->hasOne(\App\Models\InstallmentPlan::class);
    }

    public function creditNotes()
    {
        return $this->hasMany(CreditNote::class, 'invoice_id', 'id');
    }


    public static function generateInvoice($request, $estimate, $contract)
    {
        $estimate = Estimate::with('items', 'taxes', 'discounts', 'company', 'organization', 'client','installments')->where('slug', $request->slug)->first();
          DB::beginTransaction();

            $slug = self::generateInvoiceNumber();
            $invoice = new Invoice();
            $invoice->invoice_number = $slug;
            $invoice->slug = $slug;
            $invoice->client_id = $estimate->client_id;
            $invoice->company_id = $estimate->company_id;
            $invoice->created_by = $estimate->created_by;
            $invoice->estimate_id = $estimate->id;
            $invoice->issue_date = now();
            $invoice->due_date = now()->addDays(15);
            $invoice->subtotal = $estimate->subtotal;
            $invoice->total = $estimate->total;
            $invoice->note = $estimate->note;
            $invoice->terms = $estimate->terms;
            $invoice->contract_id = $contract->id;
            $invoice->status = 'unpaid';
            $invoice->save();
            
            // if($estimate->is_installment == "1"){
            //     $plan = \App\Models\InstallmentPlan::create([
            //         'invoice_id' => $invoice->id,
            //         'total_amount' => $estimate->total,
            //         'installment_count' => $estimate->installments->count(),
            //         'start_date' => $estimate->installments->first()->installment_date,
            //         'estimate_id' => $estimate->id
            //     ]);

            //     foreach ($estimate->installments as $index => $installment) {
            //         \App\Models\InstallmentPayment::create([
            //             'installment_plan_id' => $plan->id,
            //             'installment_number' => $index + 1,
            //             'invoice_id' => $invoice->id,
            //             'estimate_id' => $estimate->id,
            //             'contract_id' => $contract->id,
            //             'due_date' => $installment->installment_date,
            //             'amount' => $installment->amount,
            //             'is_paid' => false,
            //         ]);   
            //     }


            // }   

            $installments = $estimate->installments;

            if ($estimate->is_installment == "1" && $installments->isNotEmpty()) {

                $plan = \App\Models\InstallmentPlan::create([
                    'invoice_id' => $invoice->id,
                    'total_amount' => $estimate->total,
                    'installment_count' => $installments->count(),
                    'start_date' => $installments->first()->installment_date,
                    'estimate_id' => $estimate->id,
                ]);

                foreach ($installments as $index => $installment) {
                    \App\Models\InstallmentPayment::create([
                        'installment_plan_id' => $plan->id,
                        'installment_number' => $index + 1,
                        'invoice_id' => $invoice->id,
                        'estimate_id' => $estimate->id,
                        'contract_id' => $contract->id,
                        'due_date' => $installment->installment_date,
                        'amount' => $installment->amount,
                        'is_paid' => false,
                    ]);
                }
            }


            foreach ($estimate->items as $item) {
                // dd($item);
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'unit' => $item->unit,
                    'total_price' => $item->total_price,
                ]);
                ContractItem::create([
                    'contract_id' => $contract->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'price' => $item->price,
                    'total_price' => $item->total_price,
                    'taxes' => $item->tax,
                    'product_price' => $item->product_price,
                    'gratuity' => $item->gratuity,
                    'accepted_by_client' => 1,
                    'invoice_id' => $invoice->id,
                ]);
            }

            foreach ($estimate->taxes as $tax) {
                InvoiceTax::create([
                    'invoice_id' => $invoice->id,
                    'name' => $tax->name,
                    'percent' => $tax->percent,
                ]);

                ContractTaxes::create([
                    'contract_id' => $contract->id,
                    'name' => $tax->name,
                    'percent' => $tax->percent,
                    'invoice_id' => $invoice->id,
                ]);
            }

            foreach ($estimate->discounts as $discount) {
                InvoiceDiscount::create([
                    'invoice_id' => $invoice->id,
                    'name' => $discount->name,
                    'value' => $discount->value,
                ]);

                // ContractDiscountItem::create([
                //     'contract_id' => $contract->id,
                //     'name' => $discount->name,
                //     'value' => $discount->value,
                //     'invoice_id' => $invoice->id,
                // ]);
            }
        
        DB::commit();
        return $invoice;
    }

    public static function generateInvoiceForContract($modifiedContractSlug)
    {
        $contractModified = \App\Models\ContractModified::where('slug', $modifiedContractSlug)->first();

        $getContractItems = \App\Models\ContractModifiedItem::where('contract_modified_id', $contractModified->id)->get();
        $contract = \App\Models\Contract::where('id', $contractModified->contract_id)->first();

        $subTotal = (float) 0;
        $total = (float) 0;

        foreach ($getContractItems as $item) {
            $lineTotal = (float) $item->price * (int) $item->quantity;
            $subTotal += $lineTotal;
            $total += $lineTotal;
        }

        $slug = self::generateInvoiceNumber();
        $invoice = new Invoice();
        $invoice->invoice_number = $slug;
        $invoice->slug = $slug;
        $invoice->client_id = $contract->client_id;
        $invoice->company_id = $contract->company_id;
        $invoice->created_by = $contract->client_id;
        $invoice->estimate_id = $contract->id;
        $invoice->issue_date = now();
        $invoice->due_date = now()->addDays(15);
        $invoice->subtotal = $subTotal;
        $invoice->total = $total;
        $invoice->note = $contract->note;
        $invoice->terms = $contract->terms;
        $invoice->contract_id = $contract->id;
        $invoice->status = 'unpaid';
        $invoice->save();

        dd($invoice);
        foreach ($getContractItems as $item) {

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'unit' => $item->unit,
                'total_price' => $item->price * $item->quantity,
                'is_accepted_by_client' => '1',
            ]);

            ContractItem::create([
                'contract_id' => $contract->id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'price' => $item->price,
                'total_price' => $item->price * $item->quantity,
                'invoice_id' => $invoice->id,
                'is_modified' => '1',
                'is_accepted_by_client' => '1',
            ]);
        }

        return $invoice;
    }

    public static function invoiceRevision($request, $estimate, $contract)
    {
        $invoice = self::where('estimate_id', $estimate->id)->where('status', '!=', 'cancelled')->first();
        if (empty($invoice)) {
            if ($estimate->total > 0) {

                $generateInvoice = self::generateInvoice($request, $estimate, $contract);
                return $generateInvoice;
            }
        } else {
            $contract = Contract::find($contract->id);

            if ($invoice->status === 'unpaid') {

                if ($estimate->total > 0) {
                    self::where('estimate_id', $estimate->id)
                        ->update(['status' => 'cancelled']);
                    $generateInvoice = self::generateInvoice($request, $estimate, $contract);
                    return $generateInvoice;
                } else {

                    self::checkCreaditAmount($request, $estimate, $contract);
                }
            } elseif ($invoice->status === 'partial') {
                self::managePartialInvoice($request, $estimate, $contract);
            }
        }

        // return true;
    }


    public static function managePartialInvoice($request, $estimate, $contract)
    {
        // 1. Find latest partial invoice for this estimate
        $oldInvoice = self::where('estimate_id', $estimate->id)
            ->where('status', 'partial')
            ->first();

        if (!$oldInvoice) {
            return false; // No partial invoice found
        }
        // 2. Find old installment plan
        $oldPlan = InstallmentPlan::where('estimate_id', $estimate->id)->where('status', '!=', 'cancelled')->first();
        if (!$oldPlan) {
            return false; // No plan found
        }
        // 3. Count paid installments
        $paidCount = InstallmentPayment::where('installment_plan_id', $oldPlan->id)
            ->where('is_paid', 1)
            ->count();

        // 4. Calculate total paid so far
        $totalPaid = InstallmentPayment::where('estimate_id', $estimate->id)
            ->where('is_paid', 1)
            ->sum('amount');

        // 5. Cancel old unpaid installments, old plan, old invoice
        InstallmentPayment::where('estimate_id', $estimate->id)
            ->where('is_paid', 0)
            ->update(['status' => 'cancelled']);

        InstallmentPlan::where('estimate_id', $estimate->id)->update(['status' => 'cancelled']);
        self::where('estimate_id', $estimate->id)->update(['status' => 'cancelled']);

        // 6. Remaining amount check
        $remaining = $estimate->total - $totalPaid;
        $slotsLeft = 3 - $paidCount;

        // --- Case 1: Overpaid (Client ne zyada pay kar diya) ---
        if ($remaining < 0) {
            $creditNote = CreditNote::create([
                'invoice_id' => $oldInvoice->id,
                'estimate_id' => $estimate->id,
                'contract_id' => $contract->id,
                'amount' => abs($remaining),
                'reason' => 'Client overpayment adjustment',
                'status' => 'open',
            ]);

            $oldInvoice->update(['status' => 'cancelled']);
            return $creditNote;
        }

        // --- Case 2: Fully paid OR no slots left ---
        if ($remaining == 0 || $slotsLeft <= 0) {
            return true;
        }

        // 7. Create a new invoice from updated estimate
        $estimate = Estimate::with('items', 'taxes', 'discounts', 'company', 'organization', 'client')
            ->find($estimate->id);

        $slug = self::generateInvoiceNumber();

        $invoice = new Invoice();
        $invoice->invoice_number = $slug;
        $invoice->slug = $slug;
        $invoice->client_id = $estimate->client_id;
        $invoice->company_id = $estimate->company_id;
        $invoice->created_by = $estimate->created_by;
        $invoice->estimate_id = $estimate->id;
        $invoice->issue_date = now();
        $invoice->due_date = now()->addDays(15);
        $invoice->subtotal = $estimate->subtotal;
        $invoice->total = $estimate->total;
        $invoice->note = $estimate->note;
        $invoice->terms = $estimate->terms;
        $invoice->contract_id = $contract->id;
        $invoice->status = 'unpaid';
        $invoice->save();

        // 8. Copy items, taxes, discounts from estimate
        foreach ($estimate->items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'unit' => $item->unit,
                'total_price' => $item->total_price,
            ]);
        }

        foreach ($estimate->taxes as $tax) {
            InvoiceTax::create([
                'invoice_id' => $invoice->id,
                'name' => $tax->name,
                'percent' => $tax->percent,
            ]);
        }

        foreach ($estimate->discounts as $discount) {
            InvoiceDiscount::create([
                'invoice_id' => $invoice->id,
                'name' => $discount->name,
                'value' => $discount->value,
            ]);
        }

        return $invoice;
    }

    public static function checkCreaditAmount($request, $estimate, $contract)
    {
        // 4. Calculate total paid so far
        $totalPaid = InstallmentPayment::where('estimate_id', $estimate->id)
            ->where('is_paid', 1)
            ->sum('amount');
        $oldInvoice = self::where('estimate_id', $estimate->id)->where('status', 'unpaid')->first();
        // dd($oldInvoice);
        $oldInvoice->update(['status' => 'cancelled']);
        InstallmentPayment::where('estimate_id', $estimate->id)
            ->where('is_paid', 0)
            ->update(['status' => 'cancelled']);

        InstallmentPlan::where('estimate_id', $estimate->id)->update(['status' => 'cancelled']);

        $remaining = $estimate->total - $totalPaid;
        if ($remaining < 0) {
            $creditNote = CreditNote::create([
                'invoice_id' => $oldInvoice->id,
                'estimate_id' => $estimate->id,
                'contract_id' => $contract->id,
                'amount' => abs($remaining),
                'reason' => 'Client overpayment adjustment',
                'status' => 'open',
            ]);
            return $creditNote;
        }

        return true;
    }
}
