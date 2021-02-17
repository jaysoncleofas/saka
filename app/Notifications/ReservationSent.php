<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

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
        $type = '';
        if($tran->cottage) {
            $type = 'Cottage: '.$tran->cottage->name;
        } elseif($tran->room) {
            $type = 'Room: '.$tran->room->name;
        } else {
            $type = 'Exclusive Rental';
        }
        $usetype = "Use: ".ucfirst($tran->type)." use";

        if($tran->payment_id == 1) {
            return (new MailMessage)
                    ->subject("Reservation")
                    ->greeting("Hi ".$fname."!")
                    ->line("Thank you for booking with Saka Resort.")
                    // ->line("We are reviewing your reservation with a Control#".$tran->id)

                    ->line("Here are your booking details:")
                    ->line("Control Number: ". $tran->id)
                    ->line($type)
                    ->line($usetype)
                    ->line("Check in date: ". Carbon::parse($tran->checkIn_at)->toDayDateTimeString())
                    ->line("Check out date: ". Carbon::parse($tran->checkOut_at)->toDayDateTimeString())
                    ->line("Total Bill: P". number_format($tran->totalBill, 2))
                    ->line("Reservation will not be final until the amount has been settled.")
                    ->line("Payment Method: GCASH")
                    ->line("Send to our GCASH account: 01234567891")
                    ->line("Account name: NATHALIE ROSE TOH")
                    ->line("Email a screenshot of your successful transaction to ".$email." along with the following details:")
                    ->line("Email subject: Your full name, Control Number")
                    ->line("(ex. Juan Dela Cruz, Control#123)")
                    ->line("Amount paid")
                    ->line("Date and time of payment")
                    ->action("View Reservation", $url)
                    // ->line("Our customer care team will reply within one business day, however our actual reply time is often sooner.")
                    ->line("If you need to make changes or require assistance please call ".$phone." or email us at ".$email.". ")
                    ->line("We look forward to welcoming you in Saka Resort soon!");
        } elseif ($tran->payment_id == 2) {
            return (new MailMessage)
                ->subject("Reservation")
                ->greeting("Hi ".$fname."!")
                ->line("Thank you for booking with Saka Resort.")
                ->line("Here are your booking details:")
                ->line("Control Number: ". $tran->id)
                ->line($type)
                ->line($usetype)
                ->line("Check in date: ". Carbon::parse($tran->checkIn_at)->toDayDateTimeString())
                ->line("Check out date: ". Carbon::parse($tran->checkOut_at)->toDayDateTimeString())
                ->line("Total Bill: P". number_format($tran->totalBill, 2))
                ->line("Payment Method: Cash on Arrival")
                ->action("View Reservation", $url)
                // ->line("Our customer care team will reply within one business day, however our actual reply time is often sooner.")
                ->line("If you need to make changes or require assistance please call ".$phone." or email us at ".$email.". ")
                ->line("We look forward to welcoming you in Saka Resort soon!");
        } else {
            return (new MailMessage)
                ->subject("Reservation")
                ->greeting("Hi ".$fname."!")
                ->line("Thank you for booking with Saka Resort.")
                ->line("Here are your booking details:")
                ->line("Control Number: ". $tran->id)
                ->line($type)
                ->line($usetype)
                ->line("Check in date: ". Carbon::parse($tran->checkIn_at)->toDayDateTimeString())
                ->line("Check out date: ". Carbon::parse($tran->checkOut_at)->toDayDateTimeString())
                ->line("Total Bill: P". number_format($tran->totalBill, 2))
                ->line("Reservation will not be final until the amount has been settled.")
                ->line("Payment Method: Bank Deposit")
                ->line("Deposit amount to the following BPI bank account:")
                ->line("Bank of the Philippines Islands (BPI)")
                ->line("Savings Account Name: John Doe")
                ->line("Account Number: 1234-5678-90")
                ->line("Branch: Taft Avenue")
                ->line("Email a screenshot of your successful transaction to ".$email." along with the following details:")
                ->line("Email subject: Your full name, Control Number")
                ->line("(ex. Juan Dela Cruz, Control#123)")
                ->line("Amount paid")
                ->line("Bank name and branch where payment was deposited")
                ->line("Date and time of payment")
                ->action("View Reservation", $url)
                // ->line("Our customer care team will reply within one business day, however our actual reply time is often sooner.")
                ->line("If you need to make changes or require assistance please call ".$phone." or email us at ".$email.". ")
                ->line("We look forward to welcoming you in Saka Resort soon!");
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
