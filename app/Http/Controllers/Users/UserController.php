<?php
namespace App\Http\Controllers\Users;

use App\Actions\Users\ChangePassword;
use App\Actions\Users\UpdateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show the authenticated user's details.
     */
    public function show(): UserResource
    {
        return new UserResource(Auth::user());
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateUserRequest $request, UpdateUser $updateUser): UserResource
    {
        // Update user details
        $updateUser(
            user: $request->user(),
            name: $request->input('name'),
            email: $request->input('email'),
        );

        // Return the updated user details
        return new UserResource(Auth::user());
    }

    /**
     * Change the user's password.
     */
    public function changePassword(ChangePasswordRequest $request, ChangePassword $changePassword): JsonResponse
    {
        // Change user password
        $changePassword(
            user: $request->user(),
            password: $request->input('password')
        );

        return response()->json([
            'status' => 'password-changed',
        ], 200); // HTTP 200 OK for success
    }
}
