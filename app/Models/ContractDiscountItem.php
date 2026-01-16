<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractDiscountItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contract_discount_items';

    protected $fillable = [
        'contract_id',
        'name',
        'quantity',
        'unit',
        'price',
        'total_price',
        'is_modified',
        'invoice_id'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}