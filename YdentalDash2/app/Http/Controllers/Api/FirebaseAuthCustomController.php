<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use App\Http\Controllers\Controller;

class FirebaseAuthCustomController extends Controller
{
    protected $auth;

    public function __construct()
    {
        $this->auth = Firebase::auth();
    }

    /**
     * Register a new user with Firebase
     */
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            $user = $this->auth->createUser([
                'email' => $request->email,
                'password' => $request->password,
            ]);

            return response()->json([
                'message' => 'User registered successfully!',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to register user.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Login a user with Firebase
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            // Sign in with email and password
            $signInResult = $this->auth->signInWithEmailAndPassword($request->email, $request->password);

            // Get the ID token
            $idToken = $signInResult->idToken();

            return response()->json([
                'message' => 'Login successful!',
                'token' => $idToken,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed.',
                'error' => $e->getMessage(),
            ], 401);
        }
    }

    /**
     * Get authenticated user details
     */
    public function userDetails(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized. Token missing.',
            ], 401);
        }

        try {
            // Verify the Firebase token
            $verifiedIdToken = $this->auth->verifyIdToken($token);
            $uid = $verifiedIdToken->claims()->get('sub'); // Get the Firebase UID

            // Fetch user details from Firebase
            $user = $this->auth->getUser($uid);

            return response()->json([
                'message' => 'User details fetched successfully!',
                'user' => $user,
            ]);
        } catch (FailedToVerifyToken $e) {
            return response()->json([
                'message' => 'Unauthorized. Invalid token.',
            ], 401);
        } catch (UserNotFound $e) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch user details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
