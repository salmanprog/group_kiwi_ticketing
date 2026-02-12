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
            'status'     => $this->status,
            'client_id'  => $this->client_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Nested Relationships
            'items' => $this->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total,
                    'taxes' => $item->itemTaxes->map(function($tax) {
                        return [
                            'id' => $tax->id,
                            'name' => $tax->name,
                            'rate' => $tax->rate,
                        ];
                    }),
                ];
            }),

            'taxes' => $this->taxes->map(function($tax){
                return [
                    'id' => $tax->id,
                    'name' => $tax->name,
                    'rate' => $tax->rate,
                ];
            }),

            'discounts' => $this->discounts->map(function($discount){
                return [
                    'id' => $discount->id,
                    'type' => $discount->type,
                    'value' => $discount->value,
                ];
            }),

            'installments' => $this->installments->map(function($installment){
                return [
                    'id' => $installment->id,
                    'amount' => $installment->amount,
                    'due_date' => $installment->due_date,
                ];
            }),
        ];
    }
}
