<?php

namespace App\Libraries\Notification\Fcm;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
class Fcm
{
    private $_device_token, $_device_type, $_title, $_message, $_badge, $_custom_data;
    /**
     * Fcm constructor.
     * @param array $device_tokens
     * @param string $device_type  (android | ios)
     * @param string $message
     * @param int $badge
     * @param array $custom_data
     */
    public function __construct($device_tokens, $device_type, $title, $message, $badge = 0, $custom_data = [])
    {
        $this->_device_token = $device_tokens;
        $this->_message      = $message;
        $this->_device_type  = $device_type;
        $this->_title        = $title;
        $this->_badge        = $badge;
        $this->_custom_data  = $custom_data;
    }

    public function sendPushNotification()
    {
         if( $this->_device_type == 'web' ){
             return $this->_sendPushToWeb();
         } else {
             return $this->_sendPushToMobile();
         }
    }

    /**
     * This function is used to send push notification to android device
     */
    private function _sendPushToMobile()
    {
        $notification_data = [
            'notification' => [
                'title'    => $this->_title,
                'body'     => $this->_message,
                'sound'    => 'default',
                'badge'    => $this->_badge,
                'priority' => 'high',
            ],
            'data' => [
                'title' => $this->_title,
                'body'  => $this->_message,
                'user_badge'  => $this->_badge,
                //'custom_data' => $this->_custom_data,
            ]
        ];
        $this->sendPush($notification_data,$this->_device_token);
        return true;
    }

    /**
     * This function is used to send notification to web browser
     */
    private function _sendPushToWeb()
    {
        $notification_data = [
            'data' => [
                'title' => $this->_title,
                'body'  => $this->_message,
                'user_badge'  => $this->_badge,
                'custom_data' => $this->_custom_data,
            ]
        ];
        $this->sendPush($notification_data,$this->_device_token);
        return true;
    }

    protected function sendPush($notification_data,$deviceTokens)
    {
        $firebase = (new Factory)
        ->withServiceAccount(storage_path('app/firebase-adminsdk-9vnwd-2bdb8b5934.json'));

        $messaging = $firebase->createMessaging();
        $message = CloudMessage::fromArray($notification_data);
        $report = $messaging->sendMulticast($message, $deviceTokens);
        if ($report->hasFailures()) {
            $error_msg = '';
            foreach ($report->failures()->getItems() as $failure) {
                $error_msg .= $failure->error()->getMessage().PHP_EOL;
            }
            file_put_contents(base_path('notification-error.txt'),$error_msg);
        }
    }
}
