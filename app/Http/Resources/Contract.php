<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PublicUser;
use App\Http\Resources\Organization;

class Contract extends JsonResource
{
    public function toArray($request)
    {
        // dd($this->estimates);
        $subtotal = $this->items->sum(fn($item) => $item->total_price);
        // $taxTotal = $this->items->sum(fn($item) => 
        //     $item->itemTaxes->sum(fn($tax) => round($item->total_price * ($tax->percentage / 100), 2))
        // );
         $taxTotal = 0;
        // $discountPercent = $this->items->sum(fn($item) => $item->discounts->sum(fn($discount) => $discount->value));
        $discountPercent = 0;
        $total = ($subtotal + $taxTotal) * (1 - ($discountPercent / 100));
        $discountAmount = ($subtotal + $taxTotal) * ($discountPercent / 100);

        return [
            'id' => $this->id,
            'ticket_enable' => $this->ticket_enable,
            'auth_code' => $this->auth_code,
            'slug' => $this->slug,
            'contract_number' => $this->contract_number, 
            'event_date' => $this->event_date,
            'note' => $this->notes,
            'terms' => $this->terms,
            'status' => $this->status,
            'is_accept' => $this->is_accept,
            'subtotal' => $subtotal,
            'total' => $total,
            'discount_total' => $discountAmount, 
            'tax_total' => $taxTotal, 
            'signature' => $this->signature,
            'company' => $this->company,
            'user_estimate' => ($this->estimates[0] ?? null) ? [
                'slug' => $this->estimates[0]->slug,
                'issue_date' => $this->estimates[0]->issue_date,
                'valid_until' => $this->estimates[0]->valid_until,
                'event_date' => $this->estimates[0]->event_date,

            ] : null,

            'contract_modified' => $this->contractModified->map(function ($contractModified) {
                return [
                    'id' => $contractModified->id,
                    'slug' => $contractModified->slug,
                    'status' => $contractModified->status,
                    'created_at' => $contractModified->created_at,
                ];
            }),

            // Users
            'client' => new PublicUser($this->client),
            'organization' => new Organization($this->organization),

            // Contract Items
            'items' => $this->items->map(function ($item) {
                    return [
                        'id'       => $item->id,
                        'name'     => $item->name,
                        'quantity' => $item->quantity,
                        'unit'     => $item->unit,
                        'price'    => $item->price,
                        'total'    => $item->total_price,
                        'description' => $item->description,
                         'taxes'    => $item->itemTaxes->map(function ($tax) use ($item) {
                                return [
                                    'id'      => $tax->id,
                                    'name'    => $tax->name,
                                    'percent' => $tax->percentage,
                                    'amount'  => $tax->amount,
                                ];
                            }),
                    ];
            }),
              'taxes' => $this->taxes->map(function ($tax) {
                        return [
                            'id'      => $tax->id,
                            'name'    => $tax->name,
                            'percent' => $tax->percent,
                        ];
                    }),
                    // 'discounts'    => $this->discounts->map(function ($discount) {
                    //     return [
                    //         'id'    => $discount->id,
                    //         'name'  => $discount->name,
                    //         'value' => $discount->value,
                    //     ];
                    // }),
                    'discount'=>[],
                    // 'installments'=>[],
            // Invoices
            'invoices' => $this->whenLoaded('invoices', function () {
                return $this->invoices->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'invoice_number' => $invoice->invoice_number,
                        'status' => $invoice->status,

                        'installment_plan' => $invoice->installmentPlan ? [
                            'id' => $invoice->installmentPlan->id,
                            'payments' => $invoice->installmentPlan->payments,
                        ] : null,

                        'credit_notes' => $invoice->creditNotes,
                    ];
                });
            }),
        ];
    }
}
