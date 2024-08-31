<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Tickets;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    /**
     * Generate and view a ticket after booking.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateTicket(Request $request)
    {
        // Validate the request
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        // Find the booking
        $booking = Bookings::find($request->input('booking_id'));

        // Ensure the booking is confirmed
        if ($booking->status !== Bookings::STATUS_CONFIRMED) {
            return response()->json(['message' => 'Ticket can only be generated for confirmed bookings.'], 400);
        }

        // Check if a ticket already exists for this booking
        $ticket = Tickets::where('booking_id', $booking->id)->first();
        if (!$ticket) {
            // Generate the ticket
            $ticket = Tickets::create([
                'booking_id' => $booking->id,
                'ticket_number' => strtoupper(Str::random(10)),
            ]);
        }

        return response()->json([
            'message' => 'Ticket generated successfully!',
            'ticket_number' => $ticket->ticket_number,
        ], 201);
    }

    /**
     * View a ticket by booking ID.
     *
     * @param int $booking_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewTicket($booking_id)
    {
        // Find the ticket
        $ticket = Tickets::where('booking_id', $booking_id)->first();

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found.'], 404);
        }

        return response()->json([
            'ticket_number' => $ticket->ticket_number,
        ], 200);
    }
}
