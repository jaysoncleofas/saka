<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationSent extends Notification
{
    use Queueable;

    public $transaction;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
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
        $tran = $this->transaction;
        $fname = ucfirst($tran->guest->firstName);
        $url = url('/guest-transaction/'.$tran->controlCode);
        $resort = config('yourconfig.resort');
        $phone = $resort->phone;
        $email = $resort->email;

        return (new MailMessage)
                    ->subject('Reservation')
                    ->greeting('Hi '.$fname.'!')
                    ->line('Thank you for booking with Saka Resort.')
                    ->line('We are reviewing your reservation with a Control#'.$tran->id)
                    ->action('View Reservation', $url)
                    // ->line('Our customer care team will reply within one business day, however our actual reply time is often sooner.')
                    ->line('If you need to make changes or require assistance please call '.$phone.' or email us at '.$email.'. ')
                    ->line('We look forward to welcoming you in Saka Resort soon!');
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
