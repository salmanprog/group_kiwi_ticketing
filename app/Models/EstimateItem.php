<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstimateItem extends Model
{
    use CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_estimate_items';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_estimate_id',
        'product_id',
        'name',
        'description',
        'quantity',
        'unit',
        'price',
        'total_price',
        'product_price',
        'tax',
        'gratuity',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * It is used to enable or disable DB cache record
     * @var bool
     */
    protected $__is_cache_record = false;

    /**
     * @var
     */
    protected $__cache_signature;

    /**
     * @var string
     */
    protected $__cache_expire_time = 1; //days

    public function estimate()
    {
        return $this->belongsTo(Estimate::class, 'estimate_id');
    }

    public function itemTaxes()
    {
        return $this->hasMany(UserEstimateItemTax::class, 'user_estimate_item_id', 'id');
    }
    
}
