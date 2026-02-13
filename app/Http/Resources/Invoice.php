<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Company;

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


        $getCompanyAdmin = Company::getCompanyAdmin($this->company_id);
        $stripeKeyStatus = [
            'test_publishable_key' => $getCompanyAdmin->test_publishable_key,
            'test_secret_key' => $getCompanyAdmin->test_secret_key,
            'live_publishable_key' => $getCompanyAdmin->live_publishable_key,
            'live_secret_key' => $getCompanyAdmin->live_secret_key,
            'stripe_key_status' => $getCompanyAdmin->stripe_key_status,
        ];

        $estimate = $this->estimate;

        // Prevent errors if estimate not loaded
        $items = $estimate?->items ?? collect();

        /*
        |--------------------------------------------------------------------------
        | Calculations
        |--------------------------------------------------------------------------
        */

        $subtotal = $items->sum(fn ($item) => $item->total_price);

        $taxTotal = $items->sum(function ($item) {
            return $item->itemTaxes->sum(function ($tax) use ($item) {
                return $item->total_price * ($tax->percentage / 100);
            });
        });

        $discountPercent = $estimate?->discounts?->sum('value') ?? 0;

        $discountAmount = ($subtotal + $taxTotal) * ($discountPercent / 100);

        $total = ($subtotal + $taxTotal) - $discountAmount;

        return [
            'id' => $this->id,
            'payment_type' => $this->payment_type,
            'description' => $this->description,
            'slug' => $this->slug,
            'auth_code' => $this->auth_code,
            'invoice_number' => $this->invoice_number,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'is_installment' => $this->is_installment,

            /*
            |--------------------------------------------------------------------------
            | Calculated Totals
            |--------------------------------------------------------------------------
            */
            'subtotal' => round($subtotal, 2),
            'tax_total' => round($taxTotal, 2),
            'discount_total' => round($discountAmount, 2),
            'total' => round($total, 2),
            'paid_amount' => $this->paid_amount,

            /*
            |--------------------------------------------------------------------------
            | Company & Client
            |--------------------------------------------------------------------------
            */
            'company' => $this->whenLoaded('company'),
            'client'  => new PublicUser($this->whenLoaded('client')),
            'stripe_key_status' => $stripeKeyStatus,

            /*
            |--------------------------------------------------------------------------
            | Estimate Details
            |--------------------------------------------------------------------------
            */
            'details' => $this->whenLoaded('estimate', function () use ($estimate) {

                return [
                    'id'              => $estimate->id,
                    'auth_code'       => $estimate->auth_code,
                    'slug'            => $estimate->slug,
                    'estimate_number' => $estimate->estimate_number,
                    'status'          => $estimate->status,

                    'items' => $estimate->items->map(function ($item) {
                        return [
                            'id'       => $item->id,
                            'name'     => $item->name,
                            'quantity' => $item->quantity,
                            'unit'     => $item->unit,
                            'price'    => $item->product_price,
                            'total'    => $item->total_price,

                            'taxes' => $item->itemTaxes->map(function ($tax) use ($item) {
                                return [
                                    'id'      => $tax->id,
                                    'name'    => $tax->name,
                                    'percent' => $tax->percentage,
                                    'amount'  => round(
                                        $item->total_price * ($tax->percentage / 100),
                                        2
                                    ),
                                ];
                            }),
                        ];
                    }),

                    'taxes' => $estimate->taxes->map(function ($tax) {
                        return [
                            'id'      => $tax->id,
                            'name'    => $tax->name,
                            'percent' => $tax->percent,
                        ];
                    }),

                    'discounts' => $estimate->discounts->map(function ($discount) {
                        return [
                            'id'    => $discount->id,
                            'name'  => $discount->name ?? null,
                            'value' => $discount->value ?? 0,
                        ];
                    }),

                    'installments' => $estimate->installments->map(function ($installment) {
                        return [
                            'id'       => $installment->id,
                            'amount'   => $installment->amount,
                            'due_date' => $installment->installment_date,
                        ];
                    }),
                ];
            }),
        ];
    }


}
