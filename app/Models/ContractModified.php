<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ContractModified extends Model
{
    use SoftDeletes;

    protected $table = 'contract_modified';

    protected $fillable = [
        'contract_id',
        'created_by',
        'slug'
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

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}