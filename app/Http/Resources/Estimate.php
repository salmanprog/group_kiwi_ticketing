<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PublicUser;

class Estimate extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'auth_code'  => $this->auth_code,
            'slug'       => $this->slug,
            'issue_date' => $this->issue_date,
            'valid_until' => $this->valid_until,
            'event_date'       => $this->event_date,
            'note'       => $this->note,
            'terms'       => $this->terms,
            'status'       => $this->status,
            'estimate_number'       => $this->estimate_number,
            'createdBy' => new PublicUser($this->createdBy),
            'client' => new PublicUser($this->client),
            'organization' => new PublicUser($this->organization),
            'items' => $this->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product_price,
                    'total' => $item->total_price,
                    'taxes' => $item->itemTaxes->map(function($tax) {
                        return [
                            'name' => $tax->name,
                            'rate' => $tax->percentage,
                        ];
                    }),
                ];
            }),

            'taxes' => $this->taxes->map(function($tax){
                return [
                    'id' => $tax->id,
                    'name' => $tax->name,
                    'rate' => $tax->percent,
                ];
            }),

            'discounts' => $this->discounts->map(function($discount){
                return [
                    'name' => $discount->name,
                    'value' => $discount->value,
                ];
            }),

            'installments' => $this->installments->map(function($installment){
                return [
                    'id' => $installment->id,
                    'amount' => $installment->amount,
                    'due_date' => $installment->installment_date,
                ];
            }),
        ];
    }
}
