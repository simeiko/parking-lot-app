<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Ticket;
use \Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class PaymentsController extends Controller
{
    /**
     * Make the payment.
     * Soft deletes Ticket.
     *
     * @param Request $request
     * @param Ticket $ticket
     * @param int $ticket_id Ticket ID
     * @return \Illuminate\Http\Response
     */
    public function pay(Request $request, Ticket $ticket, $ticket_id)
    {
        // Validate all incoming data.

        $validator = Validator::make(
            $request->all(),
            ['credit_card' => 'required|digits:16']
        );

        // Display only first error message
        if($validator->fails()){
            return response()->json([
                'error' => $validator->getMessageBag()->first()
            ]);
        }

        // Get Ticket Details ( stay cost ).

        $ticket_details = $ticket->getTicketDetails($ticket_id);

        if($ticket_details === false)
            return response()->json( ['error' => "Given Ticket ID doesn't exist."] );

        // Soft delete Ticket.

        if(!$ticket->deleteTicket($ticket_id))
            return response()->json( ['error' => "Given Ticket ID doesn't exist."] );

        // Make payment.

        $payment = Payment::makePayment($ticket_id, $ticket_details['stay_cost']);

        if(!$payment)
            return response()->json( ['error' => "Can't make a payment."] );

        return response()->json([
            'status' => 'OK',

            'message' => 'Payment processed successfully!'
        ]);
    }
}
