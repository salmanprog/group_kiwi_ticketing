<?php

namespace App\Models;

use App\Helpers\CustomHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reset_password';

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
        'email', 'token', 'created_at', 'updated_at', 'deleted_at'
    ];

    public static function resetPassword($email)
    {
        $record = User::getUserByEmail($email);
        if( !isset($record->id) )
            return false;
        if( $record->user_type != 'admin' )
            return false;

        $token = encrypt($record->email);
        $mail_param['USERNAME'] = $record->name;
        $mail_param['LINK']     = route('admin.reset-password',['any' => $token ]);
        $mail_param['YEAR']     = date('Y');
        $mail_param['APP_NAME'] = appSetting('application_setting','application_name');
        if( env('MAIL_SANDBOX') == '0' ){
            sendMail(
                $record->email,
                'forgot-password',
                'Reset Password',
                $mail_param
            );
        }

        //delete old request
        self::where('email',$record->email)->forceDelete();
        //generate new request
        self::insert([
            'email'      => $record->email,
            'token'      => $token,
            'created_at' => Carbon::now()
        ]);
        return true;
    }

    public static function getRequest($token)
    {
        $email  = decrypt($token);
        $record = self::where('email',$email)->first();
        return $record;
    }

    public static function updateResetPassword($password,$token)
    {
        $email  = decrypt($token);
        //delete request
        self::where('email',$email)->forceDelete();
        //save new password
        DB::table('users')->where('email',$email)->update([
            'password' => Hash::make($password)
        ]);
        return true;
    }

    public static function getUserRequest($token)
    {
        $query = self::select('reset_password.*')
                    ->selectRaw('u.id AS user_id')
                    ->join('users AS u','u.email','=','reset_password.email')
                    ->where('token',$token)
                    ->first();
        return $query;
    }
}
