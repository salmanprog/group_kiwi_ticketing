<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PublicUser;
use App\Http\Resources\Organization;
use App\Http\Resources\Company;
use Carbon\Carbon;

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

          $subtotal = $this->items->sum(fn($item) => $item->total_price);
        $taxTotal = $this->items->sum(fn($item) => 
            $item->itemTaxes->sum(fn($tax) => round($item->total_price * ($tax->percentage / 100), 2))
        );
        $discountPercent = $this->discounts->sum(fn($discount) => $discount->type == 'percent' ? $discount->value : 0);
        $discountAmountFixed = $this->discounts->sum(fn($discount) => $discount->type == 'fixed' ? $discount->value : 0);

        $total = ($subtotal + $taxTotal) * (1 - ($discountPercent / 100)) - $discountAmountFixed;

        $discountAmount = ($subtotal + $taxTotal) * ($discountPercent / 100) + $discountAmountFixed; 
        $isEstimateExpire = false;
        $status = $this->status;

        if ($this->status == 'sent') {
            if (Carbon::now() > $this->valid_until) {
                $isEstimateExpire = true;
            }
        }

         if ($this->status == 'sent') {
            if (Carbon::now() > $this->valid_until) {
                $status = 'Expired';
            }
        } 

        return [
            'id'         => $this->id,
            'auth_code'  => $this->auth_code,
            'slug'       => $this->slug,
            'issue_date' => $this->issue_date,
            'valid_until' => $this->valid_until,
            'event_date'       => $this->event_date,
            'note'       => $this->note,
            'terms'       => $this->terms,
            // 'status'       => ($this->status == "sent") ? "New" : $this->status,
            'status'       => $status,
            'estimate_number'       => $this->estimate_number,
            'subtotal' => $subtotal,
            'total' => $total,
            'discount_total' => $discountAmount,
            'tax_total' => $taxTotal,
            'signature' => $this->signature,
            'is_estimate_expire'=> $isEstimateExpire,
            'company' => new Company($this->company),
            'createdBy' => new PublicUser($this->createdBy),
            'client' => new PublicUser($this->client),
            'organization' => new Organization($this->organization),
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
            'tickets' => $this->tickets->map(function($ticket){
                return [
                    'visualId' => $ticket->visualId,
                    'ticketType' => $ticket->ticketType,
                    'description' => $ticket->description,
                    'ticketDate' => $ticket->ticketDate,
                    'ticketDisplayDate' => $ticket->ticketDisplayDate,
                    'orderDisplayDate' => $ticket->orderDisplayDate,
                ];
            }),
        ];
    }
}
