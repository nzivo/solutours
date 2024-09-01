<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() {
        // Validate the registration input
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        // If validation fails, return errors with a 400 status code
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // Create a new user instance
        $user = new User;
        $user->name = request()->name;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password); // Encrypt the password
        $user->save(); // Save the user to the database

        // Automatically log in the user and generate a token
        if (! $token = auth('api')->attempt(request(['email', 'password']))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Return the user details and the token
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60, // Token lifetime in seconds
        ], 201);
    }


    /**
     * Authenticate a user and return a JWT token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        // Get the credentials from the request
        $credentials = request(['email', 'password']);

        // Attempt to authenticate the user and get the token
        if (! $token = auth('api')->attempt($credentials)) {
            // Return an error if authentication fails
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Return the token and additional information
        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated user details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        // Return the currently authenticated user
        return response()->json(auth('api')->user());
    }

    /**
     * Log out the authenticated user and invalidate the token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // Logout the user from the auth system
        auth('api')->logout();

        // Get and invalidate the current JWT token
        $token = JWTAuth::parseToken()->getToken();
        JWTAuth::invalidate($token);

        // Return a success message
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh the JWT token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        try {
            // Get the token from the Authorization header
            $token = $request->bearerToken();

            // Return an error if no token is provided
            if (!$token) {
                return response()->json(['error' => 'A token is required'], 401);
            }

            // Refresh the token and return the new token
            $newToken = JWTAuth::refresh($token);
            return $this->respondWithToken($newToken);
        } catch (JWTException $e) {
            // Return an error if the token is invalid or expired
            return response()->json(['error' => 'Token is invalid or expired'], 401);
        }
    }

    /**
     * Return a JSON response with the JWT token and related information.
     *
     * @param string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60, // Token lifetime in seconds
            'user' => auth('api')->user(),
        ]);
    }
}
