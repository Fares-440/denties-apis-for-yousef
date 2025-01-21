<?php

namespace App\Http\Middleware;

use Closure;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Illuminate\Http\Request;

class FirebaseAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized. Token missing.'], 401);
        }

        try {
            $verifiedIdToken = Firebase::auth()->verifyIdToken($token);
            $uid = $verifiedIdToken->claims()->get('sub'); // Get the Firebase UID
            $request->merge(['firebase_uid' => $uid]); // Add Firebase UID to the request
        } catch (FailedToVerifyToken $e) {
            return response()->json(['message' => 'Unauthorized. Invalid token.'], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized. Token verification failed.'], 401);
        }

        return $next($request);
    }
}
