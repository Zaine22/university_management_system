<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(8);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        // Mail::to($request->email)->send(new PasswordForget($token));

        return response()->json([
            'token' => $token,
            'message' => 'We have e-mailed your password reset link!']);

    }

    public function tokenCheck(Request $request)
    {
        $request->validate([
            'token' => 'required|exists:password_reset_tokens',
            'email' => 'required|email|exists:users',
        ]);

        $tokencheck = DB::table('password_reset_tokens')
            ->where([
                'email' => $request->email,
                'token' => $request->token,
            ])
            ->first();

        if (! $tokencheck) {

            return response()->json(['error' => 'Invalid token!']);

        }

        return response()->json(['message' => 'Token Correct!']);

    }

    public function passwordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',

            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return response()->json([
            'user' => $user,
            'message' => 'Your password has been changed!',
        ]);
    }

    public function passwordResetLaravel(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users',
            'oldPassword' => 'required|email|exists:password',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);

    }

    public function resetPasswordFrontend(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
            'oldPassword' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->oldPassword, $user->password)) {

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['message' => 'Password updated successfully.']);
        } else {
            return response()->json(['error' => 'Invalid credentials.'], 401);
        }

    }
}
