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
     * Optionally attach a PDF file.
     *
     * @param string|int $auth_code
     * @param string|array $toEmails
     * @param string $templateIdentifier
     * @param array $data
     * @param string|null $attachmentPath
     * @return void
     * @throws \Exception
     */
    public static function sendTemplate($auth_code, $toEmails, $templateIdentifier, array $data = [], $attachmentPath = null)
    {
        // Fetch email template
        $template = EmailTemplate::where('identifier', $templateIdentifier)
            ->where('auth_code', $auth_code)
            ->where('status', 1)
            ->first();

        if (!$template) {
            throw new \Exception("Template '{$templateIdentifier}' not found or inactive.");
        }

        $companyName = DB::table('company')->where('auth_code', $auth_code)->value('name');

        $subject = self::replacePlaceholders($template->subject, $data);
        if($template->identifier == "estimate_email"){
       
            $subject = $subject;
        }else if($template->identifier == "contract_email"){
            $subject = $subject;
        }
        // dd($subject);
        $body = self::replacePlaceholders($template->content, $data);

        // Fetch SMTP config for user
        $smtp = DB::table('user_smtp_settings')->where('auth_code', $auth_code)->first();

        if ($smtp) {
            // Custom SMTP
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
            Config::set('mail.from.name', $companyName);

        } else {
            // Fallback → SendGrid
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
            Config::set('mail.from.name', $companyName);
        }

        $fromEmail = ($smtp) ? $smtp->mail_no_replay : env('SENDGRID_FROM');

        // Send email using dynamic SMTP
        Mail::mailer('user_smtp')->send(new class($toEmails, $subject, $body, $fromEmail, $attachmentPath, $companyName) extends Mailable {
            public $toEmails;
            public $subject;
            public $body;
            public $fromEmail;
            public $attachmentPath;
            public $companyName;

            public function __construct($toEmails, $subject, $body, $fromEmail, $attachmentPath = null, $companyName = null)
            {
                $this->toEmails = is_array($toEmails) ? $toEmails : explode(',', $toEmails);
                $this->subject = $subject;
                $this->body = $body;
                $this->fromEmail = $fromEmail;
                $this->attachmentPath = $attachmentPath;
                $this->companyName = $companyName;
            }

            public function build()
            {
                $mail = $this->from($this->fromEmail, $this->companyName)
                             ->subject($this->subject)
                             ->html($this->body)
                             ->to($this->toEmails);

                // Attach PDF if path provided and file exists
                if ($this->attachmentPath && file_exists($this->attachmentPath)) {
                    $mail->attach($this->attachmentPath, [
                        'as' => basename($this->attachmentPath),
                        'mime' => 'application/pdf',
                    ]);
                }

                return $mail;
            }
        });
    }

    /**
     * Replace placeholders in template content
     *
     * @param string $text
     * @param array $data
     * @return string
     */
    protected static function replacePlaceholders($text, $data)
    {
        $currentYear = date('Y');
        foreach ($data as $key => $value) {
            $text = str_replace(['{{ $'.$key.' }}', '{{'.$key.'}}'], $value, $text);
        }
        $text = str_replace(['{{ $currentYear }}', '{{currentYear}}'], $currentYear, $text);
        return $text;
    }
}