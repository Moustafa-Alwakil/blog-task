<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = new User;

        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->password = $request->get('password');

        if ($request->hasFile('avatar')) {
            $user->avatar = Str::afterLast($request->file('avatar')->store(User::$storage, 'public'), '/');
        }

        $user->save();

        return $this->login(
            (new LoginRequest())->merge([
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'device_name' => 'first_login'
            ])
        );
    }

    public function login(LoginRequest $request)
    {
        $user = User::query()
            ->where('email', $request->get('email'))
            ->firstOrFail();

        $attemptUser = auth()->attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ], remember: true);

        if ($attemptUser) {
            /** @var User $user */
            $user = auth('sanctum')->user();

            $token = $user->createToken($request->get('device_name'));

            return response()->json([
                'token' => $token->plainTextToken,
                'user' => new UserResource($user),
            ]);
        }

        throw ValidationException::withMessages(['email' => 'These credentials does not match our records.']);
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = auth('sanctum')->user();

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged Out Successfully.'
        ]);
    }
}
