<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organizations';

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
        'organization_type_id',
        'event_type_id',
        'event_history_id',
        'client_id',
        'company_id',
        'name',
        'contact',
        'slug',
        'department',
        'address_one',
        'address_two',
        'city',
        'state',
        'zip',
        'country',
        'email',
        'phone',
        'fax',
        'size',
        'rep',
        'group_size',
        'first_meeting',
        'hot_button',
        'closing_probability',
        'event_date',
        'event_status',
        'next_objective',
        'follow_up_date',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
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

    public function organizationType()
    {
        return $this->belongsTo(OrganizationType::class, 'organization_type_id');
    }

    public function eventHistory()
    {
        return $this->belongsTo(EventHistoryType::class, 'event_history_id');
    }

    public function eventType()
    {
        return $this->belongsTo(Event::class, 'event_type_id');
    }

    public function contract()
    {
        return $this->hasMany(Contract::class, 'organization_id', 'id');
    }

    public function estimate()
    {
        return $this->hasMany(Estimate::class, 'organization_id', 'id');
    }
}
