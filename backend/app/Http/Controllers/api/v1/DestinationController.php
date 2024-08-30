<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
    public function show(string $id)
    {
        $destination = Destination::findOrFail($id);
        return response()->json($destination, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
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
        $destination = Destination::findOrFail($id);
        $destination->delete();
        return response()->json(null, 204);
    }
}
