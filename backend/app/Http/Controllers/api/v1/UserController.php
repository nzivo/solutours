<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to view users.'], 403);
        }
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to view a user.'], 403);
        }
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => config('constants.USER_NOT_FOUND')], 404);
        }

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to assign user role.'], 403);
        }
        // Validate the request
        $request->validate([
            'role' => 'required|string|in:admin,user',
        ]);

        // Find the user by ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => config('constants.USER_NOT_FOUND')], 404);
        }

        // Update the user's role
        $user->role = $request->input('role');
        $user->save();

        return response()->json(['message' => 'Role updated successfully!'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'You do not have permission to delete a user.'], 403);
        }
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => config('constants.USER_NOT_FOUND')], 404);
        }
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully!'], 200);
    }
}
