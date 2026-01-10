<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class UserConnectAccount extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_connect_account';

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
        'user_id','first_name','last_name','date_of_birth','ssn','id_front','id_back','city',
        'state','street','phone','postal_code','status','due_fields','created_at','updated_at','deleted_at'
    ];

    public static function saveConnectAccount($params)
    {
        $updated_params['user_id'] = $params['user']->id;
        if( !empty($params['first_name']) ){
            $updated_params['first_name'] = $params['first_name'];
        }
        if( !empty($params['last_name']) ){
            $updated_params['last_name'] = $params['last_name'];
        }
        if( !empty($params['dob']) ){
            $updated_params['date_of_birth'] = $params['dob'];
        }
        if( !empty($params['city']) ){
            $updated_params['city'] = $params['city'];
        }
        if( !empty($params['state']) ){
            $updated_params['state'] = $params['state'];
        }
        if( !empty($params['street']) ){
            $updated_params['street'] = $params['street'];
        }
        if( !empty($params['phone']) ){
            $updated_params['phone'] = $params['phone'];
        }
        if( !empty($params['postal_code']) ){
            $updated_params['postal_code'] = $params['postal_code'];
        }
        if( !empty($params['ssn']) ){
            $updated_params['ssn'] = $params['ssn'];
        }
        if( !empty($params['due_fields']) ){
            $updated_params['due_fields'] = json_encode($params['due_fields']);
        }
        $updated_params['status']     = 'pending';
        $updated_params['created_at'] = now();
        $updated_params['updated_at'] = now();

        if( !empty($params['id_front']) ){
            $id_front_name = 'document/'. md5($params['user']->gateway_connect_id) . '_id_front.jpg';
             //upload encrypt image
            Storage::put($id_front_name, Crypt::encrypt($params['id_front']->getContent()));
            $updated_params['id_front'] = $id_front_name;
        }
        if( !empty($params['id_back']) ){
            $id_back_name  = 'document/'. md5($params['user']->gateway_connect_id) . '_id_back.jpg';
            //upload encrypt image
            Storage::put($id_back_name, Crypt::encrypt($params['id_back']->getContent()));
            $updated_params['id_back'] = $id_back_name;
        }
        $record = self::updateOrCreate(
            ['user_id' => $params['user']->id],
            $updated_params
        );
        return $record;
    }

    public static function getConnectAccount($user_id)
    {
        $query = self::where('user_id',$user_id)->first();
        return $query;
    }
}
