<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    /**
     * Create Ticket if there is at least one available parking lot.
     *
     * @return Ticket|bool Ticket on success; false on fail.
     */
    public function createTicket()
    {
        if($this->countNotDeleted() >= env('MAX_PARKING_LOTS'))
            return false;

        $ticket = new Ticket;

        return $ticket->save() ? $ticket : false;
    }

    /**
     * Soft delete ticket by ID.
     *
     * @param int $ticket_id Ticket ID
     * @return bool True on success; false on fail.
     */
    public function deleteTicket(int $ticket_id)
    {
        $ticket = $this->find($ticket_id);

        if(!$ticket) return false; // Ticket doesn't exist

        try {
            $ticket->delete(); // Try to delete Ticket
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Count all not deleted tickets.
     *
     * @return int|null Integer on success; null on fail.
     */
    public function countNotDeleted()
    {
        try {
            return $this->where('deleted_at', null)->count();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get ticket details.
     * Returns associative array.
     *
     * @param int $ticket_id Ticket ID
     * @return array|bool Array with ticket details; false if ticket doesn't exist.
     */
    public function getTicketDetails(int $ticket_id)
    {
        $stay_duration = $this->getStayDuration($ticket_id);

        if (is_null($stay_duration)) return false;

        $stay_cost = $this->getStayCost($stay_duration);

        if($stay_cost === false) return false;

        return [
            'stay_duration' => $stay_duration,
            'stay_cost' => $stay_cost
        ];
    }

    /**
     * Get stay duration in minutes.
     * Based on creation date ( created_at field ).
     *
     * @param int $ticket_id Ticket ID
     * @return int|null Stay in minutes; null on fail.
     */
    public function getStayDuration(int $ticket_id)
    {
        try {
            $ticket = $this->find($ticket_id);

            return Carbon::parse($ticket->created_at)->diffInMinutes();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get stay cost.
     * Uses ParkingRates model to determine the price per hour.
     *
     * @todo Add support for "ticket_id" parameter.
     *
     * @param int $stay_duration Stay duration in minutes
     * @return bool|float Stay cost as a float value; false on fail.
     */
    public function getStayCost(int $stay_duration)
    {
        foreach ((new ParkingRates)->orderBy('price_per_hour')->get() as $rate) {
            if ($stay_duration <= $rate['max_minutes']) {
                return $stay_duration * ($rate['price_per_hour'] / 60);
            }
        }

        return false;
    }
}