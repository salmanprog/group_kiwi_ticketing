<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractDiscount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contract_discounts';

    protected $fillable = [
        'contract_id',
        'name',
        'percent',
        'is_modified',
        'invoice_id',
        'amount'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}