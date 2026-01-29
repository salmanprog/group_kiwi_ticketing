<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractModifiedInstallment extends Model
{
    use SoftDeletes;

    protected $table = 'contract_modified_installments';

    protected $fillable = [
        'contract_modified_id',
        'contract_id',
        'amount',
        'installment_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

}