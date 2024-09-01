<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Tickets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketsController extends Controller
{
    public function getAllTickets()
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to view all tickets.'], 403);
        }
        // Fetch all tickets with related bookings, tours, destinations, and users
        $tickets = Tickets::with([
            'booking',
            'booking.tour',
            'booking.tour.destination',
            'booking.user' // Load the user associated with the booking
        ])
        ->get();

        return response()->json($tickets);
    }

    public function getMyTickets()
    {
        $userId = Auth::id(); // Get the ID of the currently authenticated user

        // Fetch tickets with related bookings, tours, and destinations
        $tickets = Tickets::whereHas('booking', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with([
            'booking',
            'booking.tour',
            'booking.tour.destination'
        ])
        ->get();

        return response()->json($tickets);
    }
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
            'id' =>  $ticket->id,
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
        // Find the booking
        $booking = Bookings::find($booking_id);

        // Find the ticket with related models
        $ticket = Tickets::where('booking_id', $booking_id)
            ->with(['booking.user', 'booking.tour.destination']) // Eager load related models
            ->first();

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found.'], 404);
        }

        // Check if the authenticated user is the one who made the booking or is an admin
        if ($booking->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to view this ticket.'], 403);
        }

        // Format the response
        return response()->json($ticket);
    }

}
