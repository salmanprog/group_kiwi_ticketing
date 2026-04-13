<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PublicUser;
use App\Http\Resources\Organization;
use App\Http\Resources\Company;
use Carbon\Carbon;

class ContractModified extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $subtotal = $this->items->sum(fn($item) => $item->total_price);
        $taxTotal = $this->items->sum(fn($item) => 
            $item->itemTaxes->sum(fn($tax) => round($item->total_price * ($tax->percentage / 100), 2))
        );
        $discountPercent = $this->discounts->sum(fn($discount) => $discount->type == 'percent' ? $discount->value : 0);
        $discountAmountFixed = $this->discounts->sum(fn($discount) => $discount->type == 'fixed' ? $discount->value : 0);

        $total = ($subtotal + $taxTotal) * (1 - ($discountPercent / 100)) - $discountAmountFixed;

        $discountAmount = ($subtotal + $taxTotal) * ($discountPercent / 100) + $discountAmountFixed; 
        $status = $this->status;
        $company = ($this->contract && $this->contract->company) ? new Company($this->contract->company) : null;
        $client = ($this->contract && $this->contract->client) ? new PublicUser($this->contract->client) : null;
        $organization = ($this->contract && $this->contract->organization) ? new Organization($this->contract->organization) : null;
        $estimate_slug = ($this->contract && $this->contract->estimateone) ? $this->contract->estimateone->slug : null;
        return [
            'id'         => $this->id,
            'auth_code'  => $this->auth_code,
            'slug'       => $this->slug,
            'estimate_number' => $estimate_slug,
            'issue_date' => $this->issue_date,
            'valid_until' => $this->valid_until,
            'event_date'       => $this->event_date,
            'note'       => $this->note,
            'terms'       => $this->terms,
            'status'       => $status,
            'subtotal' => $subtotal,
            'total' => $total,
            'discount_total' => $discountAmount,
            'tax_total' => $taxTotal,
            'signature' => $this->signature,
            'company' => new Company($company),
            'createdBy' => new PublicUser($this->createdBy),
            'client' => new PublicUser($client),
            'organization' => new Organization($organization),
            'items' => $this->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->total_price,
                    'description' => $item->description,
                    'taxes' => $item->itemTaxes->map(function($tax) {
                        return [
                            'name' => $tax->name,
                            'rate' => $tax->percentage,
                            'amount' => $tax->amount,
                        ];
                    }),
                ];
            }),

            'taxes' => $this->taxes->map(function($tax){
                return [
                    'id' => $tax->id,
                    'name' => $tax->name,
                    'rate' => $tax->percent,
                    'amount' => $tax->amount,
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
