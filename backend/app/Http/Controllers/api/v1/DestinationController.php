<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DestinationController extends Controller
{
    public function generateUniqueSlug($slug)
    {
        // Initial slug
        $originalSlug = $slug;

        // Check if the slug exists
        $count = 1;
        while (Destination::where('slug', $slug)->exists()) {
            // Append the number to the slug
            $slug = $originalSlug . '_' . $count;
            $count++;
        }

        return $slug;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Destination::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to create a destination.'], 403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|regex:/^[a-zA-Z0-9_-]+$/',
            'description' => 'nullable|string',
        ]);

        // Generate a unique slug
        $slug = $this->generateUniqueSlug($request->slug);

        // Create the destination
        $destination = new Destination();
        $destination->name = $request->name;
        $destination->slug = $slug;
        $destination->description = $request->description;
        $destination->save();

        return response()->json(['message' => 'Destination created successfully!'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json(['message' => config('constants.DESTINATION_NOT_FOUND')], 404);
        }

        return response()->json($destination);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to update a destination.'], 403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|regex:/^[a-zA-Z0-9_-]+$/',
            'description' => 'nullable|string',
        ]);

        // Generate a unique slug only if it's changed
        $slug = $request->slug === $destination->slug
            ? $request->slug
            : $this->generateUniqueSlug($request->slug);

        // Update the destination
        $destination->name = $request->name;
        $destination->slug = $slug;
        $destination->description = $request->description;
        $destination->save();

        return response()->json(['message' => 'Destination updated successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to delete a destination.'], 403);
        }
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json(['message' => config('constants.DESTINATION_NOT_FOUND')], 404);
        }
        $destination = Destination::findOrFail($id);
        $destination->delete();
        return response()->json(['message' => 'Destination deleted successfully!'], 200);
    }
}
