<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\User\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function __invoke(UpdateProfileRequest $request)
    {
        /** @var User $user */
        $user = auth('sanctum')->user();

        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->filled('new_password')) {
            $user->password = $request->get('new_password');
        }

        if ($request->hasFile('avatar')) {
            $user->deleteAvatar();
            $user->avatar = Str::afterLast($request->file('avatar')->store(User::$storage, 'public'), '/');
        }

        $user->save();

        return response()->json([
            'message' => 'Your Profile Has Been Updated Successfully.',
            'user' => new UserResource($user),
        ]);
    }
}
