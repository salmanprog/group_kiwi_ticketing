<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractModifiedItem extends Model
{
    use SoftDeletes;

    protected $table = 'contract_modified_items';

    protected $fillable = [
        'contract_id',
        'contract_modified_id',
        'name',
        'quantity',
        'unit',
        'price',
        'total_price',
        'is_modified',
        'invoice_id',
    ];

    /**
     * Get the parent modification record.
     */
    public function modification()
    {
        return $this->belongsTo(ContractModified::class, 'contract_modified_id');
    }

    /**
     * Get the contract this item belongs to.
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the invoice associated with this item.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}