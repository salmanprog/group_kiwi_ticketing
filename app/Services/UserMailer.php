<?php

namespace App\Services;

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class UserMailer
{
    /**
     * Send an email using a stored template and user-specific SMTP.
     *
     * @param string|int $auth_code
     * @param string|array $toEmails
     * @param string $templateIdentifier
     * @param array $data
     * @return void
     * @throws \Exception
     */
    public static function sendTemplate($auth_code, $toEmails, $templateIdentifier, array $data = [])
    {
        $template = EmailTemplate::where('identifier', $templateIdentifier)
            ->where('status', 1)
            ->first();

        if (!$template) {
            throw new \Exception("Template '{$templateIdentifier}' not found or inactive.");
        }

        $subject = self::replacePlaceholders($template->subject, $data);
        $body = self::replacePlaceholders($template->content, $data);

        $smtp = DB::table('user_smtp_settings')->where('auth_code', $auth_code)->first();
        if (!$smtp) {
            throw new \Exception("SMTP configuration not found for this user.");
        }

        // Apply dynamic SMTP config temporarily
        Config::set('mail.mailers.user_smtp', [
            'transport' => 'smtp',
            'host' => $smtp->mail_host,
            'port' => $smtp->mail_port,
            'encryption' => $smtp->mail_encryption ?: null,
            'username' => $smtp->mail_username,
            'password' => $smtp->mail_password,
            'timeout' => null,
            'auth_mode' => null,
        ]);

        // Send email using dynamic SMTP
        Mail::mailer('user_smtp')->send(new class($toEmails, $subject, $body, $smtp->mail_no_replay) extends Mailable {
            public $toEmails;
            public $subject;
            public $body;
            public $fromEmail;

            public function __construct($toEmails, $subject, $body, $fromEmail)
            {
                $this->toEmails = is_array($toEmails) ? $toEmails : explode(',', $toEmails);
                $this->subject = $subject;
                $this->body = $body;
                $this->fromEmail = $fromEmail;
            }

            public function build()
            {
                return $this->from($this->fromEmail, 'No Reply')
                            ->subject($this->subject)
                            ->html($this->body)
                            ->to($this->toEmails);
            }
        });
    }

    protected static function replacePlaceholders($text, $data)
    {
        foreach ($data as $key => $value) {
            $text = str_replace(['{{ $'.$key.' }}', '{{'.$key.'}}'], $value, $text);
        }
        return $text;
    }
}