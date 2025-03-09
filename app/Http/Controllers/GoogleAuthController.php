<?php

namespace App\Http\Controllers;

use App\Http\Requests\FrontendGoogleApiRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(12)),
                ]
            );

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            Log::error('Google OAuth error: ', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Unable to authenticate the user.'], 500);
        }
    }

    public function frontendGoogle(FrontendGoogleApiRequest $request)
    {

        $googleId = $request['googleId'];
        $googleEmail = $request['email'];

        $user = User::where('email', $googleEmail)->first();

        if (! $user) {

            $user = User::create([
                'name' => $request['name'],
                'email' => $googleEmail,
                'google_id' => $googleId,
                'password' => Hash::make(Str::random(12)),

            ]);

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);

        } else {

            // $user = User::updateOrCreate(
            //     ['email' => $googleEmail],
            //     [
            //         'name' => $request->name,
            //         'google_id' => $request->googleId,
            //         'password' => Hash::make(Str::random(12)),
            //     ]
            // );

            // $token = $user->createToken('API Token')->plainTextToken;

            // return response()->json([
            //     'user' => $user,
            //     'token' => $token,
            // ]);

            if ($user->google_id !== $googleId) {
                return response()->json(['message' => 'Google ID does not match.'], 401);
            }
            // Generate a new token for the user
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('API Token')->plainTextToken;
            $cookie = cookie('api_token', $token, 60 * 24 * 7); // Cookie for API Token

            return response()->json([
                'user' => $user,
                'token' => $token,
            ])->cookie($cookie);
        }

    }
}
