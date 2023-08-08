<?php

namespace App\Notifications\Frontend\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Site\SiteEmails;
use Session;

/**
 * Class UserNeedsConfirmation.
 */
class UserNeedsConfirmation extends Notification
{
    use Queueable;

    /**
     * @var
     */
    protected $confirmation_code;

    /**
     * UserNeedsConfirmation constructor.
     *
     * @param $confirmation_code
     */
    public function __construct($confirmation_code)
    {
        $this->confirmation_code = $confirmation_code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $siteEmail = SiteEmails::join('email_types', 'site_emails.email_type_id', '=', 'email_types.id')
            ->select('site_emails.*')
            ->where('site_emails.site_id', '=', Session::get('site_id'))
            ->where('email_types.name', '=', 'Confirm New Account')
            ->get();

        foreach ($siteEmail as $email) {
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


            return (new MailMessage())
                ->subject($email->subject)
                ->line($email->body)
                ->action(trans('buttons.emails.auth.confirm_account'), route('frontend.auth.account.confirm', $this->confirmation_code));
        }
    }
}
