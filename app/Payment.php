<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * Make payment.
     *
     * @param int $ticket_id Ticket ID
     * @param float $paid_amount Paid amount
     * @return bool True if payment processed successfully.
     */
    public static function makePayment(int $ticket_id, float $paid_amount)
    {
        $payment = new Payment();

        $payment->ticket_id = $ticket_id;
        $payment->paid_amount = $paid_amount;

        return $payment->save();
    }
}
