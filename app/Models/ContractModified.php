<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\ContractModified;

class ContractModified extends Model
{
    use SoftDeletes;
    use CRUDGenerator;

    protected $table = 'contract_modified';

    protected $fillable = [
        'contract_id',
        'created_by',
        'user_estimate_id',
        'slug',
        'status'
    ];

    // Automatically assign the user ID when creating
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::id();
        });
    }


    public function items()
    {
        return $this->hasMany(ContractModifiedItem::class, 'contract_modified_id');
    }
    
    public function taxes()
    {
        return $this->hasMany(ContractModifiedTax::class, 'contract_modified_id');
    } 

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function discounts()
    {
        return $this->hasMany(ContractModifiedDiscount::class, 'contract_modified_id');
    }

    public function installments()
    {
        return $this->hasMany(ContractModifiedInstallment::class, 'contract_modified_id');
    }
    
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public static function updateModifyOrder($request)
    {
        $ContractModified = ContractModified::with([
                        'items',
                        'taxes' ,
                        'installments',
                        'discounts'
                    ])->where('id',$request['contract_modified_id'])->first();


          if ($ContractModified) {

            $get_updated_estimate = ContractModified::with('items.itemTaxes')->with('taxes')->with('discounts')->with('installments')->where('id', $request['contract_modified_id'])->first();

            $subtotal = $get_updated_estimate->items->sum(fn($item) => $item->total_price);

            $taxTotal = $get_updated_estimate->items->sum(fn($item) => 
                $item->itemTaxes->sum(fn($tax) => round($item->total_price * ($tax->percentage / 100), 2))
            );

            
            $discountPercent = $get_updated_estimate->discounts->sum(fn($discount) => $discount->value);
            $total = ($subtotal + $taxTotal) * (1 - ($discountPercent / 100));
            $discountAmount = ($subtotal + $taxTotal) * ($discountPercent / 100);

            $get_updated_estimate->update(['subtotal' => $subtotal,'total' => $total,'discount_total' => $discountAmount,'tax_total' => $taxTotal]);

            $update_contract = Contract::find($request['contract_id']);
            $update_contract->total += $get_updated_estimate->total;
            $update_contract->save();
            $obj = json_decode(json_encode($request));

            Invoice::generateModifyInvoice($obj->contract_id,$obj,$update_contract->auth_code);                    
        }
        // dd($ContractModified);
    }


}
