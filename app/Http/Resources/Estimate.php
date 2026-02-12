<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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

            // Nested Relationships
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
                    'rate' => $tax->percentage,
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
