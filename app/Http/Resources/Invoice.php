<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Company;
use App\Models\Estimate;
use DB;

class Invoice extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $invoice = Invoice::findOrFail($this->id);
        $estimate = Estimate::where('contract_id', $invoice->contract_id)->first();
        $getCompanyAdmin = Company::getCompanyAdmin($invoice->company_id);

        $companyAccount = DB::table('company_account_config')->where('company_id', $invoice->company_id)->first();
        $stripeKeyStatus = [
            'test_publishable_key' => $companyAccount?->test_publishable_key ?? null,
            'live_publishable_key' => $companyAccount?->live_publishable_key ?? null,
            'stripe_key_status' => $companyAccount?->stripe_key_status ?? null,
        ];

        // Prevent errors if items not loaded
        $items = $this->invoiceItems ?? collect();

        /*
        |--------------------------------------------------------------------------
        | Calculations
        |--------------------------------------------------------------------------
        */

        $subtotal = $this->invoiceItems->sum(fn ($item) => $item->total_price);

        $taxTotal = 0;
        // dd($taxTotal);

        $discountPercent = $invoice->discounts?->sum('value') ?? 0;

        $discountAmount = ($subtotal + $taxTotal) * ($discountPercent / 100);

        $total = ($subtotal + $taxTotal) - $discountAmount;

        return [
            'id' => $invoice->id,
            'payment_type' => $invoice->payment_type,
            'description' => $invoice->description,
            'slug' => $invoice->slug,
            'auth_code' => $invoice->auth_code,
            'invoice_number' => $invoice->invoice_number,
            'issue_date' => $invoice->issue_date,
            'due_date' => $invoice->due_date,
            'status' => $invoice->status,
            'is_installment' => $invoice->is_installment,

            /*
            |--------------------------------------------------------------------------
            | Calculated Totals
            |--------------------------------------------------------------------------
            */
            'subtotal' => round($subtotal, 2),
            'tax_total' => round($taxTotal, 2),
            'discount_total' => round($discountAmount, 2),
            'total' => round($total, 2),
            'paid_amount' => $invoice->paid_amount,

            /*
            |--------------------------------------------------------------------------
            | Company & Client
            |--------------------------------------------------------------------------
            */
            'company' => $invoice->company,
            'client'  => new PublicUser($invoice->client),
            'stripe_key_status' => $stripeKeyStatus,

            /*
            |--------------------------------------------------------------------------
            | Estimate Details
            |--------------------------------------------------------------------------
            */
            'details' =>  [
                    'id'              => $invoice->id,
                    'estimate_id'     => $estimate->id,
                    'auth_code'       => $invoice->auth_code,
                    'slug'            => $invoice->slug,
                    'estimate_number' => $estimate->estimate_number,
                    'status'          => $invoice->status,

                    'items' => $items ?? [],

                    'taxes' => $invoice->taxes ?? [],

                    'discounts' => $invoice->discounts ?? [],

                    'installments' => $invoice->installmentPlan?->payments->map(function ($installment) {
                        return [
                            'id'       => $installment->id,
                            'amount'   => $installment->amount,
                            'status'   => $installment->status,
                            'due_date' => $installment->due_date,
                        ];
                    }) ?? [],
                ]
        ];
    }


}
