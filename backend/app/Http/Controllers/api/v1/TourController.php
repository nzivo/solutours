<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Tours;
use Illuminate\Http\Request;

class TourController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tours = Tours::all();
        return response()->json($tours);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tours $tour)
    {
        $tour = Tours::find($tour);

        if (!$tour) {
            return response()->json(['message' => config('constants.TOUR_NOT_FOUND')], 404);
        }
        // Validate the request
        $request->validate([
            'destination_id' => 'required|exists:destination,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'slots' => 'required|integer|min:1',
        ]);

        // Update the tour
        $tour->name = $request->name;
        $tour->description = $request->description;
        $tour->price = $request->price;
        $tour->slots = $request->slots;
        $tour->destination_id = $request->destination_id;
        $tour->save();

        return response()->json(['message' => 'Tour updated successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tour = Tours::find($id);

        if (!$tour) {
            return response()->json(['message' => config('constants.TOUR_NOT_FOUND')], 404);
        }
        $tour = Tours::findOrFail($id);
        $tour->delete();
        return response()->json(['message' => 'Tour deleted successfully!'], 200);
    }
}
