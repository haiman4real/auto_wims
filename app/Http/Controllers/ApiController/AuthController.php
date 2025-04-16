<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Exception;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        // Validate the input data
        $validator = Validator::make($request->all(), [
            'user_name' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
        ]);


        // Return validation errors, if any
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 422
            ], 422);
        }

        // Check if a user with the provided email already exists
        $existingUser = User::where('email', '=', $request->client_id)->first();



        if ($existingUser) {
            if ($existingUser->status === 'disabled') {
                return response()->json([
                    'message' => 'Some error occured. Please contact the Administrator',
                    'status' => 403
                ], 403);
            }

            Log::warning('Registration attempt with existing email.', [
                'email' => $request->client_id,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'message' => 'User with this email already exists.',
                'status' => 409
            ], 409); // 409 Conflict response
        }

        // create client_secret using a strong cyptographic random string
        $client_secret = bin2hex(random_bytes(16));


        try {
            // Create the new user
            $user = User::create([
                'user_name' => $request->user_name,
                'email' => $request->client_id,
                'password' => Hash::make($client_secret),
                'user_phone' => "+2340000000000",
                'user_level' => 3,
                'user_role' => "AdminThree",
                'user_station' => "HQ",
                'user_station_no' => 1,
            ]);
            $user->save();

            // Fire the Registered event (optional)
            event(new Registered($user));

            // Generate Passport token for the new user
            $tokenResult = $user->createToken('authToken');
            $accessToken = $tokenResult->accessToken;

            // Log the registration event
            Log::info('User registered successfully.', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);

            // Return the success response with the token and user data
            return response()->json([
                'success' => true,
                'message' => 'Registration successful.',
                'client_id' => $request->client_id,
                'client_secret' => $client_secret,
                'user' => $user,
                'status' => 200,
                'token_type' => 'Bearer',
                'expires_in' => 86400, // Optional: 1 day expiration
                'access_token' => $accessToken
            ], 200);
        } catch (Exception $e) {
            // Log any unexpected errors during registration
            Log::error('Registration failed.', [
                'error' => $e->getMessage(),
                'email' => $request->client_id,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'message' => 'Registration failed. Please try again.',
                'status' => 500
            ], 500);
        }
    }

    public function login(Request $request)
    {
        // Validate the request input
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|email',
            'client_secret' => 'required|string|min:6',
        ]);

        // Return validation errors, if any
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all(),
                'status' => 422
            ], 422);
        }

        // Attempt to find the user by email
        $user = User::where('email', $request->client_id)->first();

        if (!$user) {
            Log::error('User does not exist.', ['email' => $request->client_id, 'ip' => $request->ip()]);

            return response()->json([
                'message' => 'User does not exist.',
                'status' => 422
            ], 422);
        }

        if ($user->status === 'disabled') {
            return response()->json([
                'message' => 'Account is deleted. Please contact support.',
                'status' => 403
            ], 403);
        }

        // Check if the password matches
        if (!Hash::check($request->client_secret, $user->password)) {
            Log::error('Password mismatch.', ['email' => $request->client_id, 'ip' => $request->ip()]);

            return response()->json([
                'message' => 'Invalid credentials.',
                'status' => 422
            ], 422);
        }

        // Generate Passport personal access token
        $tokenResult = $user->createToken('authToken');
        $accessToken = $tokenResult->accessToken; // Extract the plain text token

        // Log successful token retrieval
        Log::info('Token generated successfully.', [
            'user_id' => $user->id,
            'ip' => $request->ip()
        ]);

        // Return the success response with the access token and user data
        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'user' => $user->user_name,
            'status' => 200,
            'token_type' => 'Bearer',
            'expires_in' => 86400, // Optional: 1 day expiration
            'access_token' => $accessToken
        ], 200);
    }

    /**
     * Logout the user (revoke the token)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout(Request $request)
    {

        $token = $request->user()->token();
        $token->revoke();

        Log::info("Logout successful for ".$request->clientid." from IP - ". $request->ip());


        // Return a success response
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
            'status' => 200,
        ], 200);
    }
}
