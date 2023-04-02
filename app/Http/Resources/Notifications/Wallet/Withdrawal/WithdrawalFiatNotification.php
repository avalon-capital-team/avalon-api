<?php

namespace App\Notifications\Wallet\Withdrawal;

use App\Models\Withdrawal\WithdrawalFiat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalFiatNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $withdrawal;

    /**
     * Create a new notification instance.
     * @param \App\Models\Withdrawal\WithdrawalFiat $withdrawal
     * @return void
     */
    public function __construct(WithdrawalFiat $withdrawal)
    {
        $this->withdrawal = $withdrawal;
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
        if ($this->withdrawal->status_id == 1) {
            return (new MailMessage())->view('emails.wallet.withdrawal.withdrawal_fiat_rejected', ['withdrawal' => $this->withdrawal])->subject('[Split Assets] Saque estornado no valor de '.currency_format($this->withdrawal->amount));
        } elseif ($this->withdrawal->status_id == 2) {
            return (new MailMessage())->view('emails.wallet.withdrawal.withdrawal_fiat_approved', ['withdrawal' => $this->withdrawal])->subject('[Split Assets] Saque aprovado no valor de '.currency_format($this->withdrawal->amount));
        } else {
            return (new MailMessage())->view('emails.wallet.withdrawal.withdrawal_fiat_request', ['withdrawal' => $this->withdrawal])->subject('[Split Assets] Saque solicitado no valor de '.currency_format($this->withdrawal->amount));
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
