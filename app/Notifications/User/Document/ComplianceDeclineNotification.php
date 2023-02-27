<?php

namespace App\Notifications\User\Document;

use App\Models\User\UserCompliance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplianceDeclineNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $userCompliance;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(UserCompliance $userCompliance)
    {
        $this->userCompliance = $userCompliance;
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
        return (new MailMessage())->view(
            'emails.user.document.compliance_decline',
            ['userCompliance' => $this->userCompliance]
        )->subject('[Split Assets] Seus documentos foram recusados');
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
