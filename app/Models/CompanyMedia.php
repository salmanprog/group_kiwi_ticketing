<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMedia extends Model
{
     use CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_media';

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
        'auth_code', 'filename', 'original_name', 'file_url', 'mime_type',
        'file_type', 'driver', 'media_type', 'meta', 'created_at', 'updated_at', 'deleted_at', 'slug'
    ];
    
    public static function generateSlug() {
        $slug = uniqid();
        while (self::where('slug', $slug)->exists()) {
            $slug = uniqid();
        }
        return $slug;
    }
}
