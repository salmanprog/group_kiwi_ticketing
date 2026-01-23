<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use HasFactory, SoftDeletes, CRUDGenerator;

    protected $fillable = [
        'slug',
        'contract_number',
        'client_id',
        'company_id',
        'title',
        'salutation',
        'ticket_rate',
        'catering_menu',
        'catering_price',
        'deposite_amount',
        'hours',
        'alt_contact',
        'note_history',
        'contract_status',
        'status',
        'total',
        'terms',
        'notes',
        'event_date',
        'is_accept',
        'terms_and_condition',
    ];

    public static function generateUniqueSlug()
    {
        do {
            $slug = 'con-' . rand(1000, 9999);
        } while (self::where('slug', $slug)->exists());

        return $slug;
    }



    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function userestimates()
    {
        return $this->belongsTo(Estimate::class, 'organization_id');
    }

    public function estimates()
    {
        return $this->hasMany(Estimate::class, 'contract_id')->whereIn('status', ['approved', 'revised']);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'contract_id')
            ->join('user_estimate as estimates', 'estimates.id', '=', 'user_invoices.estimate_id')
            ->select('user_invoices.*', 'estimates.slug as estimate_slug')
            ->orderby('user_invoices.created_at', 'desc');
    }

    public function items()
    {
        return $this->hasMany(ContractItem::class, 'contract_id');
    }
}
