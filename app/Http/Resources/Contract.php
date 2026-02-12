<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PublicUser;

class Contract extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'auth_code' => $this->auth_code,
            'slug' => $this->slug,
            'contract_number' => $this->contract_number,
            'event_date' => $this->event_date,
            'note' => $this->note,
            'terms' => $this->terms,
            'status' => $this->status,
            'is_accept' => $this->is_accept,

            // Users
            'client' => new PublicUser($this->whenLoaded('client')),
            'organization' => new PublicUser($this->whenLoaded('organization')),

            // Contract Items
            'details' => $this->estimates->map(function ($estimate) {
                return [
                    'id'              => $estimate->id,
                    'auth_code'       => $estimate->auth_code,
                    'slug'            => $estimate->slug,
                    'estimate_number' => $estimate->estimate_number,
                    'status'          => $estimate->status,

                    // Items + nested itemTaxes
                    'items' => $estimate->items->map(function ($item) {
                        return [
                            'id'       => $item->id,
                            'name'     => $item->name,
                            'quantity' => $item->quantity,
                            'unit'     => $item->unit,
                            'price'    => $item->product_price,
                            'total'    => $item->total_price,
                            'taxes'    => $item->itemTaxes->map(function ($tax) use ($item) {
                                return [
                                    'id'      => $tax->id,
                                    'name'    => $tax->name,
                                    'percent' => $tax->percentage,
                                    'amount'  => round($item->total_price * ($tax->percentage / 100), 2),
                                ];
                            }),
                        ];
                    }),

                    'taxes'        => $estimate->taxes->map(function ($tax) {
                        return [
                            'id'      => $tax->id,
                            'name'    => $tax->name,
                            'percent' => $tax->percent,
                        ];
                    }),
                    'discounts'    => $estimate->discounts->map(function ($discount) {
                        return [
                            'id'    => $discount->id,
                            'name'  => $discount->name,
                            'value' => $discount->value,
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
            // Discounts
            // 'discounts' => $this->whenLoaded('discounts', function () {
            //     return $this->discounts->map(function ($discount) {
            //         return [
            //             'id' => $discount->id,
            //             'name' => $discount->name,
            //             'value' => $discount->value,
            //         ];
            //     });
            // }),

            // Installments
            // 'installments' => $this->whenLoaded('installments', function () {
            //     return $this->installments->map(function ($installment) {
            //         return [
            //             'id' => $installment->id,
            //             'amount' => $installment->amount,
            //             'due_date' => $installment->installment_date,
            //         ];
            //     });
            // }),

            // Estimates
            // 'estimates' => $this->whenLoaded('estimates', function () {
            //     return $this->estimates->map(function ($estimate) {
            //         return [
            //             'id' => $estimate->id,
            //             'slug' => $estimate->slug,
            //             'estimate_number' => $estimate->estimate_number,
            //             'status' => $estimate->status,

            //             'items' => $estimate->items->map(function ($item) {
            //                 return [
            //                     'id' => $item->id,
            //                     'name' => $item->name,
            //                     'quantity' => $item->quantity,
            //                     'price' => $item->product_price,
            //                     'total' => $item->total_price,
            //                 ];
            //             }),

            //             'taxes' => $estimate->taxes,
            //             'discounts' => $estimate->discounts,
            //             'installments' => $estimate->installments,
            //         ];
            //     });
            // }),

            // Invoices
            // 'invoices' => $this->whenLoaded('invoices', function () {
            //     return $this->invoices->map(function ($invoice) {
            //         return [
            //             'id' => $invoice->id,
            //             'invoice_number' => $invoice->invoice_number,
            //             'status' => $invoice->status,

            //             'installment_plan' => $invoice->installmentPlan ? [
            //                 'id' => $invoice->installmentPlan->id,
            //                 'payments' => $invoice->installmentPlan->payments,
            //             ] : null,

            //             'credit_notes' => $invoice->creditNotes,
            //         ];
            //     });
            // }),
        ];
    }
}
