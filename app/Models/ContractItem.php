<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractItem extends Model
{
    use HasFactory, SoftDeletes, CRUDGenerator;

    protected $table = 'contract_items';

    protected $fillable = [
        'contract_id',
        'name',
        'quantity',
        'unit',
        'price',
        'total_price',
        'is_modified',
        'invoice_id',
        'is_accepted_by_client',
        'product_price',
        'taxes',
        'gratuity',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

}
