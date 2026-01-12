<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use Storage;

class Company extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company';

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
        'id', 'name','slug', 'image_url', 'address', 'mobile_no', 'email', 'website', 'description', 'status', 'created_at', 'updated_at', 'deleted_at'
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
    protected $__cache_expire_time = 1; 

       public static function generateUniqueSlug($slug)
    {
        $username = Str::slug($slug);
        $query = self::where('slug',$slug)->count();
        if( $query > 0){
            $slug = $slug . $query . rand(111,999);
        }
        return Str::slug($slug);
    }

    public function companyAdmin()
    {
        return $this->hasOne(CompanyUser::class,'company_id','id')->where('user_type','admin');
    }

    public static function getCompanyAdmin($id)
    {
        $query = self::with('companyAdmin.user')->find($id);
        return $query->companyAdmin->user;
    }

    public function organizations()
    {
        return $this->hasMany(Organization::class, 'company_id', 'id');
    }

    public function companymanager()
    {
        return $this->hasMany(CompanyUser::class, 'company_id', 'id')->where('user_type','manager');
    }

    public function companysalesman()
    {
        return $this->hasMany(CompanyUser::class, 'company_id', 'id')->where('user_type','salesman');
    }

    public function estimates()
    {
        return $this->hasMany(Estimate::class, 'company_id', 'id');
    }
}
