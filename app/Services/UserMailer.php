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

           if (
        $smtp &&
        !empty($smtp->mail_host) &&
        !empty($smtp->mail_username) &&
        !empty($smtp->mail_password) &&
        !empty($smtp->$smtp->mail_port)
            ) {
                // ✅ USE CUSTOM SMTP
                Config::set('mail.mailers.user_smtp', [
                    'transport' => 'smtp',
                    'host' => $smtp->mail_host,
                    'port' => $smtp->mail_port,
                    'encryption' => $smtp->mail_encryption ?: null,
                    'username' => $smtp->mail_username,
                    'password' => $smtp->mail_password,
                    'timeout' => null,
                ]);

                Config::set('mail.from.address', $smtp->mail_no_replay);
                Config::set('mail.from.name', 'No Reply');

                \Log::info('Using CUSTOM SMTP');

            } else {
                // ✅ FALLBACK → SENDGRID
                Config::set('mail.mailers.user_smtp', [
                    'transport' => 'smtp',
                    'host' => env('SENDGRID_HOST', 'smtp.sendgrid.net'),
                    'port' => env('SENDGRID_PORT', 587),
                    'encryption' => env('SENDGRID_ENCRYPTION', 'tls'),
                    'username' => env('SENDGRID_USERNAME', 'apikey'),
                    'password' => env('SENDGRID_PASSWORD'),
                    'timeout' => null,
                ]);

                Config::set('mail.from.address', env('SENDGRID_FROM'));
                Config::set('mail.from.name', env('SENDGRID_FROM_NAME', 'System'));

                \Log::info('Using SENDGRID FALLBACK');
            }
        // if (!$smtp) {
        //     throw new \Exception("SMTP configuration not found. Please configure SMTP settings first.");
        // }

        // // Apply dynamic SMTP config temporarily
        // Config::set('mail.mailers.user_smtp', [
        //     'transport' => 'smtp',
        //     'host' => $smtp->mail_host,
        //     'port' => $smtp->mail_port,
        //     'encryption' => $smtp->mail_encryption ?: null,
        //     'username' => $smtp->mail_username,
        //     'password' => $smtp->mail_password,
        //     'timeout' => null,
        //     'auth_mode' => null,
        // ]);

        // Send email using dynamic SMTP
        $fromEmail = ($smtp) ? $smtp->mail_no_replay : env('SENDGRID_FROM');
        // dd($fromEmail);
        Mail::mailer('user_smtp')->send(new class($toEmails, $subject, $body, $fromEmail) extends Mailable {
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