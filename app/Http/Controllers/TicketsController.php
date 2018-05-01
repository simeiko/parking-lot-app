<?php

namespace App\Http\Controllers;

use App\ParkingRates;
use App\Ticket;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    /**
     * Store a newly created Ticket in the database.
     * Returns "ticket_id" or "error" message in a JSON format.
     *
     * @param Ticket $ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Ticket $ticket)
    {
        $ticket = $ticket->createTicket();

        if(!$ticket)
            return response()->json( ['error' => "There is no available parking lot."] );

        return response()->json( ['ticket_id' => $ticket['id']] );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Ticket $ticket
     * @param integer $ticket_id Ticket ID
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket, $ticket_id)
    {
        $ticket = $ticket->getTicketDetails($ticket_id);

        return response()->json([
            'stay_duration' => $ticket['stay_duration'],
            'stay_cost' => $ticket['stay_cost']
        ]);
    }

    /**
     * Count all not deleted tickets.
     *
     * @param  \App\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function count(Ticket $ticket)
    {
        $count = $ticket->countNotDeleted();

        if(is_null($count))
            return response()->json( ['error' => "Can't fetch data."] );

        $freeLots = env('MAX_PARKING_LOTS') - $count;

        return response()->json( ['free_lots' => $freeLots] );
    }
}
