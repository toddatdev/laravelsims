<?php

namespace App\Notifications\Frontend\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Site\SiteEmails;
use Session;

class PasswordReset extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;    

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // Need to get the siteEmails message
        $siteEmail = SiteEmails::join('email_types', 'site_emails.email_type_id', '=', 'email_types.id')
            ->select('site_emails.*')
            ->where('site_emails.site_id', '=', Session::get('site_id'))
            ->where('email_types.name', '=', 'Password Reset')
            ->get();

        foreach ($siteEmail as $email) {
            // Need to replace TinyMCE Tags and remove HTML Tags out of Subject
            $siteEmailSubjectArr = [
                '<p>' => '',
                '</p>' => '',
                '&nbsp;' => ' ', // Don't think is necessary but I've seen these tags here before
                '{{first_name}}' => $notifiable->first_name,
                '{{last_name}}' => $notifiable->last_name
            ];
    
            // Reset Value
            $subjectReplacement = str_replace(array_keys($siteEmailSubjectArr), array_values($siteEmailSubjectArr), $email->subject);
            $email->subject = $subjectReplacement;

            // Do same with body
            $siteEmailBodyArr = [
                '{{first_name}}' => $notifiable->first_name,
                '{{last_name}}' => $notifiable->last_name
            ];
    
            $bodyReplacement = str_replace(array_keys($siteEmailBodyArr), array_values($siteEmailBodyArr), $email->body);
            $email->body = $bodyReplacement;

            // Create email and token. using r/v/v/n/email.blade.php as template, this is what is used is Laravel pwd reset default  
            return (new MailMessage)
                ->subject($email->subject)
                ->line($email->body) // Form Body
                ->action('Reset Password', url('password/reset', $this->token));
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
