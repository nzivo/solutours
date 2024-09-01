<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Tours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tours = Tours::with('destination')->get();
        return response()->json($tours);
    }

    /**
     * Admin only can Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to create a tour.'], 403);
        }
        // Validate the request
        $request->validate([
            'destination_id' => 'required|exists:destination,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'slots' => 'required|integer|min:1',
        ]);

        // Create the tour
        $tour = Tours::create($request->all());

        return response()->json($tour, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tour = Tours::find($id);

        if (!$tour) {
            return response()->json(['message' => config('constants.TOUR_NOT_FOUND')], 404);
        }

        return response()->json($tour);
    }


    /**
     * Admin only can Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to update a tour.'], 403);
        }
        // Validate the request
        $request->validate([
            'destination_id' => 'required|exists:destination,id', // Ensure the table name is correct
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'slots' => 'required|integer|min:1',
        ]);

        // Find the tour by ID
        $tour = Tours::find($id);

        if (!$tour) {
            return response()->json(['message' => config('constants.TOUR_NOT_FOUND')], 404);
        }

        // Update the tour with validated data
        $tour->name = $request->input('name');
        $tour->description = $request->input('description');
        $tour->price = $request->input('price');
        $tour->slots = $request->input('slots');
        $tour->destination_id = $request->input('destination_id');
        $tour->save();

        return response()->json(['message' => 'Tour updated successfully!'], 200);
    }


    /**
     * Admin only can Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to delete a tour.'], 403);
        }
        $tour = Tours::find($id);

        if (!$tour) {
            return response()->json(['message' => config('constants.TOUR_NOT_FOUND')], 404);
        }
        $tour = Tours::findOrFail($id);
        $tour->delete();
        return response()->json(['message' => 'Tour deleted successfully!'], 200);
    }
}
