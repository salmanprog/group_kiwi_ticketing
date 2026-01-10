<?php

namespace App\Libraries\Sms;

class Sms
{
    public function getInstance()
    {
        $sms_driver = env('SMS_DRIVER');
        if( $sms_driver == 'None' ){
            throw new Exception("SMS driver is not defined");
        }
        $gateway_file_path = '\App\Libraries\Sms\\' . $sms_driver . '\\' . $sms_driver;
        if( file_exists($gateway_file_path) ){
            throw new Exception( $sms_driver . ' class is not found');
        }
        return new $gateway_file_path;
    }
}
