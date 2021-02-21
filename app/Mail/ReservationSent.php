<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Resort;
use App\Models\Entrancefee;

class ReservationSent extends Mailable
{
    use Queueable, SerializesModels;
    public $transaction, $resort, $entranceFees;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction, Resort $resort)
    {
        $entranceFees = Entrancefee::all();
        $this->transaction = $transaction;
        $this->resort = $resort;
        $this->entranceFees = $entranceFees;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Saka Reservation')->markdown('emails.reservations.sent');
    }
}
