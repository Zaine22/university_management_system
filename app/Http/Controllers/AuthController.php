<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\StudentApiRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->only(['email', 'password']));

        if (! Auth::attempt($request->only(['email', 'password']))) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('API Token')->plainTextToken;
        $cookie = cookie('api_token', $token, 60 * 24 * 7);

        return $this->success([
            'user' => $user,
            'token' => $token,
        ])->cookie($cookie);
    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->only(['name', 'email', 'password']));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('API Token')->plainTextToken;
        $cookie = cookie('api_token', $token);

        return $this->success([
            'user' => $user,
            'token' => $token,
        ])->cookie($cookie);
    }

    // public function edit()
    // {
    //     $password = bcrypt(auth()->user()->password);
    //     return $password;
    // }
    public function update(Request $request)
    {

        $old_password = $request['old_password'];
        $password_confirmation = $request['password_confirmation'];

        $check = Hash::check($old_password, auth()->user()->password);

        $user = User::find(auth()->id());

        if ($check == true) {
            $user->update(['password' => Hash::make($password_confirmation)]);

            return $this->success('Successfully updated');
        } else {
            return $this->error('', 'Error Updating Password', 422);
        }

    }

    public function store(StudentApiRequest $request) {}

    // public function logout()
    // {
    //     Auth::user()->currentAccess()->delete();

    //     return $this->success([
    //         'message' => 'You have succesfully been logged out and your token has been removed',
    //     ]);
    // }
}
