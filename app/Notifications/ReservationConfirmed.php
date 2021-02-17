<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ReservationConfirmed extends Notification
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
        $type = '';
        if($tran->cottage) {
            $type = 'Cottage: '.$tran->cottage->name;
        } elseif($tran->room) {
            $type = 'Room: '.$tran->room->name;
        } else {
            $type = 'Exclusive Rental';
        }
        $usetype = ucfirst($tran->type).' use';
        return (new MailMessage)
                    ->subject('Reservation Confirmation')
                    ->greeting('Hi '.$fname.'!')
                    ->line('Thank you for choosing Saka Resort. We look forward to hosting your stay.')
                    // ->line('Here are your booking details:')
                    // ->line('Reservation Control Number:'. $tran->id)
                    // ->line($type)
                    // ->line($usetype)
                    // ->line('Check in date:'. Carbon::parse($tran->checkIn_at)->toDayDateTimeString())
                    // ->line('Check out date:'. Carbon::parse($tran->checkOut_at)->toDayDateTimeString())
                    ->action('View Reservation', $url)
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
