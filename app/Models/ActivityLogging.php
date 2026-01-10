<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Crypt;

class ActivityLogging extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_logging';

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
        'user_id','ip_address','user_agent','http_method','route_name','url','request_payload',
        'request_header','http_status_code','response','content_type','created_at', 'updated_at'
    ];

    public static function saveActivityLog($request,$response)
    {
        if( $request->is('api/*') ){
            $user_id   = 0;
            $payload   = $request->all();
            //remove sensitive information
            unset(
                $payload['password'],
                $payload['confirm_password'],
                $payload['current_password'],
                $payload['new_password'],
            );
            if( !empty($request->header('user-token')) ){
                 if( !isset($request['user']->id) )
                    return false;

                $user_id = $request['user']->id;
            }
            if( $response->headers->get('Content-Type') == 'application/json' ){
                $content = Crypt::encryptString(json_encode($response->getData()));
            }else if( $response->headers->get('Content-Type') == 'text/html; charset=UTF-8' ){
                $content = Crypt::encryptString($response->getContent());
            } else {
                $content = null;
            }
            $http_response = $content;
            self::insert([
                'user_id'          => $user_id,
                'ip_address'       => $request->ip(),
                'user_agent'       => json_encode($request->header('user-agent')),
                'http_method'      => $request->method(),
                'route_name'       => Route::currentRouteName(),
                'url'              => $request->url(),
                'request_payload'  => Crypt::encryptString(json_encode($payload)),
                'request_header'   => Crypt::encryptString(json_encode($request->header())),
                'http_status_code' => $response->getStatusCode(),
                'response'         => $http_response,
                'content_type'     => $response->headers->get('Content-Type'),
                'created_at'       => Carbon::now()
            ]);
        }
        return true;
    }
}
