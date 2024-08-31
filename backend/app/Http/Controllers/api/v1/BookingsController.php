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
}
