<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
class Estimate extends Model
{
    use SoftDeletes, CRUDGenerator;

    protected $table = 'user_estimate';
    protected $primaryKey = 'id';

    protected $fillable = [
        'client_id',
        'slug',
        'auth_code',
        'created_by',
        'estimate_number',
        'company_id',
        'organization_id',
        'issue_date',
        'valid_until',
        'note',
        'terms',
        'status',
        'subtotal',
        'total',
        'tax_total',
        'discount_total',
        'is_open',
        'event_date',
        'contract_id',
        'is_adjusted',
        'terms_and_condition',
        'is_installment',
    ];

    /**
     * Relations
     */
    public function items()
    {
        return $this->hasMany(EstimateItem::class, 'user_estimate_id', 'id');
    }

    public function taxes()
    {
        return $this->hasMany(EstimateTax::class, 'estimate_id', 'id');
    }

    public function discounts()
    {
        return $this->hasMany(EstimateDiscount::class, 'estimate_id', 'id');
    }

    public function creditNotes()
    {
        return $this->hasMany(EstimateCreditNote::class, 'estimate_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function invoices()
    {
        return $this->hasOne(Invoice::class, 'estimate_id');
    }

    public function estimateinvoices()
    {
        return $this->hasMany(Invoice::class, 'estimate_id', 'id');
    }

    public function installments()
    {
        return $this->hasMany(EstimateInstallment::class, 'estimate_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    /**
     * Generate unique slug
     */
    public static function generateUniqueSlug()
    {
        do {
            $slug = 'est-' . rand(1000, 9999);
        } while (self::where('slug', $slug)->exists());

        return $slug;
    }

    public function logActivity($description, $oldData = [], $newData = [])
    {
        ActivityLog::create([
            'module' => 'estimate',
            'module_id' => $this->id,
            'description' => $description,
            'user_id' => Auth::id(),
            'old_data' => json_encode($oldData),
            'new_data' => json_encode($newData),
        ]);
    }
}
