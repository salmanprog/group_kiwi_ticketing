<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization_users';

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
        'organization_id','company_id','auth_code','slug','client_id','created_by','first_name','last_name','email','mobile_no','position','title','salutation','ticket_rate','catering_menu','catering_price','deposite_amount','hours','alt_contact','note_history','contract_status','created_at','updated_at','deleted_at'
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

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id','id');
    }

    public function estimates()
    {
        return $this->hasMany(Estimate::class, 'client_id', 'id');
    }

    public function invoices()
    {
        return $this->hasManyThrough(
            Invoice::class,   // final model
            Estimate::class,  // intermediate model
            'client_id',      // Estimate foreign key to Client
            'estimate_id',    // Invoice foreign key to Estimate
            'id',             // Client primary key
            'id'              // Estimate primary key
        );
    }
}
