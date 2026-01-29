<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEstimateItemTax extends Model
{
    use HasFactory;

    // Explicitly define the table name if it doesn't follow Laravel's pluralization
    protected $table = 'user_estimate_item_taxes';

    // Mass assignable attributes
    protected $fillable = [
        'estimate_tax_id',
        'user_estimate_item_id',
        'name',
        'percentage',
    ];

    /**
     * Get the item that owns the tax.
     */
    public function estimateItem()
    {
        return $this->belongsTo(UserEstimateItem::class, 'user_estimate_item_id');
    }
}