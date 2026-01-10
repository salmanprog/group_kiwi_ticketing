<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefaultEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $to, $email_view, $subject, $params, $attachment_path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        $email_view,
        $subject,
        $params,
        $attachment_path
    )
    {
        $this->email_view      = $email_view;
        $this->params          = $params;
        $this->subject         = $subject;
        $this->attachment_path = $attachment_path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
                    ->subject($this->subject)
                    ->view('email.' . $this->email_view)
                    ->with($this->params);

        if( !empty($this->attachment_path) ){
            foreach( $this->attachment_path as $attach ){
                $mail->attach($attach);
            }
        }
        return $mail;
    }
}
