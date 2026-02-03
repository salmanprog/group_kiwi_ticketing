<?php

namespace App\Models;

use App\Helpers\CustomHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyAdmin extends Authenticatable
{
    use HasFactory, CRUDGenerator, SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

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
        'user_group_id', 'user_type', 'name', 'username', 'slug', 'email', 'auth0_id', 'company_name', 'auth_code', 'mobile_no', 'password', 'image_url','blur_image','status',
        'platform_type','platform_id','is_email_verify', 'email_verify_at', 'is_mobile_verify', 'mobile_verify_at', 'country', 'city', 'state',
        'zipcode', 'address', 'latitude', 'longitude', 'online_status','mobile_otp', 'email_otp', 'remember_token',
        'gateway_customer_id','gateway_connect_id','created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token'
    ];

    /**
     * It is used to enable or disable DB cache record
     * @var bool
     */
    protected $__is_cache_record = true;

    /**
     * @var
     */
    protected $__cache_signature;

    /**
     * @var string
     */
    protected $__cache_expire_time = 1; //days

    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class,'user_group_id','id');
    }

    public function userApiToken()
    {
        return $this->hasMany(UserApiToken::class,'user_id','id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'id','user_id');
    }


    /**
     * Authentication
     * @param {int} $user_group_id
     * @param {string} $email
     * @param {string} password
     * @param {bool} $remember_me
     * @return {bool}
     */
    public static function auth($user_type, $email,$password, $remember_me = false)
    {
        $credentials = [
            'user_type' => $user_type,
            'email'     => $email,
            'password'  => $password
        ];
        if( Auth::attempt($credentials,$remember_me) ) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Get Auth User
     * @param {string} @guard | optional
     */
    public static function getAuthUser($guard = 'web')
    {
        if( Auth::check() ){
            $user = Auth::guard($guard)->user();
            $user->user_group = $user->userGroup;
            return $user;
        } else {
            return [];
        }

    }

    /**
     * Update Admin Profile
     * @param {Array} $params
     * @return {bool}
     */
    public static function updateProfile($params)
    {
        if( !empty($params['image_url']) )
            $profile_image = uploadMedia('users',$params['image_url']);
        else
            $profile_image = $params['old_file'];

        self::where('id',currentUser()->id)
            ->update([
                'name'      => $params['name'],
                'email'     => $params['email'],
                'mobile_no' => $params['mobile_no'],
                'image_url' => $profile_image
            ]);
        return true;
    }

    /**
     * Update Admin Password
     * @param {int} $id
     * @param {string} $password
     * @return {bool}
     */
    public static function updatePassword($id,$password)
    {
        self::where('id',$id)->update([
            'password' => Hash::make($password)
        ]);
        return true;
    }

    /**
     * This function is used to generate unique username
     * @param string $username
     * @return string $username
     */
    public static function generateUniqueUserName($username)
    {
        $username = Str::slug($username);
        $query = self::where('username',$username)->count();
        if( $query > 0){
            $username = $username . $query . rand(111,999);
        }
        return Str::slug($username);
    }

    /**
     * This function is used to get user by email
     * @param $email
     * @return object $query
     */
    public static function getUserByEmail($email)
    {
        $query = self::where('email',$email)->first();
        return $query;
    }

    /**
     * This function is used to update user device token
     * @param illuminate\http\request $request
     * @param object $user
     * @return bool
     */
    public static function updateDeviceToken($request,$user, $platform_type = 'custom')
    {
        $api_token  = UserApiToken::generateApiToken($user->id,$request->ip(),$request->header('token'),$user->created_at);
        $record = UserApiToken::updateOrCreate(
            ['api_token' => $api_token],
            [
                'user_id'       => $user->id,
                'api_token'     => $api_token,
                'refresh_token' => UserApiToken::generateRefreshToken($user->id),
                'udid'          => $request->header('token'),
                'device_type'   => $request['device_type'],
                'device_token'  => $request['device_token'],
                'platform_type' => $platform_type,
                'platform_id'   => $request['platform_id'],
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->server('HTTP_USER_AGENT'),
                'created_at'    => Carbon::now(),
            ]
        );
        //new device login attempt
        if( !$record->wasChanged() ){

        }
        return $api_token;
    }

    /**
     * @param $email
     * @param string $module
     * @return false|object
     */
    public static function ForgotPassword($email, $module = 'users')
    {
        $user = self::getUserByEmail($email);
        if( !isset($user->id) )
            return false;
        elseif( $user->status != 1)
            return false;

        $reset_pass_token = Str::random(150);
        ResetPassword::insert([
            'email'      => $email,
            'token'      => $reset_pass_token,
            'created_at' => Carbon::now(),
        ]);
        //send reset password email
        if( env('MAIL_SANDBOX') == 0 ){
            $mail_params['username'] = $user->name;
            $mail_params['link']     = route('reset-password',['any' => $reset_pass_token]);
            sendMail(
                $user->email,
                'forgot-password',
                'Forgot Password Confirmation',
                $mail_params
            );
        }
        return $user;
    }

    public static function updateUser($user_id,$data)
    {
        self::where('id',$user_id)->update($data);
    }

    public static function updateUserByEmail($email,$data)
    {
        self::where('email',$email)->update($data);
    }

    public static function getUserByApiToken($api_token)
    {
        $user = self::select('users.*')
                    ->selectRaw('
                        uat.api_token,
                        uat.device_type,
                        uat.device_token,
                        users.platform_type,
                        users.platform_id
                    ')
                    ->join('user_api_token AS uat','uat.user_id','=','users.id')
                    ->where('uat.api_token',$api_token)
                    ->first();
        return $user;
    }

    public static function userLogout($params)
    {
        UserApiToken::where('api_token',$params['api_token'])->forceDelete();
        return true;
    }

    public static function socialUser($params)
    {
        $image_url  = null;
        $blur_image = null;
        $data = new \stdClass();
        //get user by platform id & platform type
        $user = self::where('platform_type',$params['platform_type'])->where('platform_id',$params['platform_id'])->first();
        if( empty($user) ){
            if( !empty($params['email']) ){
                $checkUserByEmail = self::getUserByEmail($params['email']);
                if( isset($checkUserByEmail->id) ){
                    $user = $checkUserByEmail;
                }
            }
        }

        //upload image by url
        if( !empty($params['image_url']) ){
            $image_content = @file_get_contents($params['image_url']);
            if( !empty($image_content) ){
                $image_url  = CustomHelper::uploadMediaByContent('users',$image_content);
                $blur_image = CustomHelper::getBlurHashImage(Storage::path($image_url));
            }
        }
        //create new user
        if( !isset($user->id) ){
            $created_at    = Carbon::now();
            $temp_password = Str::random(8);
            $username      = self::generateUniqueUserName($params['name']);
            $record_id = self::insertGetId([
                'user_group_id'   => 2,
                'name'            => $params['name'],
                'username'        => $username,
                'slug'            => $username,
                'email'           => !empty($params['email']) ? $params['email'] : null,
                'password'        => Hash::make($temp_password),
                'mobile_no'       => !empty($params['mobile_no']) ? $params['mobile_no'] : null,
                'image_url'       => $image_url,
                'blur_image'      => $blur_image,
                'is_email_verify' => 1,
                'platform_type'   => $params['platform_type'],
                'platform_id'     => $params['platform_id'],
                'latitude'        => !empty($params['latitude']) ? $params['latitude'] : null,
                'longitude'       => !empty($params['longitude']) ? $params['longitude'] : null,
                'created_at'      => Carbon::now(),
            ]);
            $data->id = $record_id;
            $data->created_at = $created_at;
        } else {
            //update existing user
            $update_data = [];

            $update_data['updated_at']      = Carbon::now();
            if( !empty($update_data) )
                self::where('id',$user->id)->update($update_data);

            $data->id = $user->id;
            $data->created_at = $user->created_at;
        }
        return $data;
    }

    public static function getUserByPlatformID($platform_type,$platform_id)
    {
        $query = self::select('users.*')
                    ->selectRaw('api_token,device_type,device_token,platform_type,platform_id')
                    ->join('user_api_token AS uat','uat.user_id','=','users.id')
                    ->where('platform_type',$platform_type)
                    ->where('platform_id',$platform_id)
                    ->first();
        return $query;
    }

    public static function getUserApiTokenByID($user_id)
    {
        $query = self::select('users.*','uat.device_type','uat.device_token')
                    ->join('user_api_token AS uat','uat.user_id','=','users.id')
                    ->where('uat.user_id',$user_id)
                    ->get();
        return $query;
    }
}
