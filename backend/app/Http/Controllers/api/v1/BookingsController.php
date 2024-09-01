<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Bookings;
use App\Models\Tours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingsController extends Controller
{
    /**
     * Get all Bookings by admin.
     */
    public function getBookings(){
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to view all bookings.'], 403);
        }

        // Retrieve all bookings with related user, tour, and destination details
        $bookings = Bookings::with(['user', 'tour.destination'])->get();

        return response()->json($bookings);
    }

    /**
     * returns active user bookings.
     */
    public function getMyBookings(){
        // Retrieve the authenticated user's bookings with related tour, destination, and tickets
        $bookings = Bookings::where('user_id', Auth::id())
            ->with(['tour.destination', 'tickets']) // Eager load related tour, destination, and tickets
            ->get();

        return response()->json($bookings);
    }

    /**
     * Book a tour.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bookTour(Request $request)
    {
        // Validate the request
        $request->validate([
            'tour_id' => 'required|exists:tours,id',
        ]);

        // Find the tour
        $tour = Tours::find($request->input('tour_id'));

        if (!$tour) {
            return response()->json(['message' => config('constants.TOUR_NOT_FOUND')], 404);
        }

        // Check if there are available slots
        if ($tour->slots <= 0) {
            return response()->json(['message' => 'No available slots for this tour.'], 400);
        }

        // Create the booking
        $booking = Bookings::create([
            'user_id' => Auth::id(),
            'tour_id' => $tour->id,
            'status' => Bookings::STATUS_PENDING,
        ]);

        // Decrease the available slots
        $tour->slots -= 1;
        $tour->save();

        return response()->json([
            'message' => 'Tour booked successfully!',
            'booking_id' => $booking->id,
            'status' => $booking->status,
        ], 201);
    }

    /*
        A function to confirm a tour booking
        accepts only booking_id
    */
    public function confirmBooking($booking_id)
    {
        // Find the booking
        $booking = Bookings::find($booking_id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }

        // Check if the authenticated user is the one who made the booking or is an admin
        if ($booking->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to confirm this booking.'], 403);
        }

        // Check if the booking is already confirmed
        if ($booking->status === Bookings::STATUS_CONFIRMED) {
            return response()->json(['message' => 'Booking is already confirmed.'], 400);
        }

        // Confirm the booking
        $booking->status = Bookings::STATUS_CONFIRMED;
        $booking->save();

        return response()->json(['message' => 'Booking confirmed successfully!', 'status' => $booking->status], 200);
    }
}
