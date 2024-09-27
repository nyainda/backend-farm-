<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendPasswordResetLinkRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class SendPasswordResetLinkController extends Controller
{
    public function __invoke(SendPasswordResetLinkRequest $request): JsonResponse
    {
        $status = Password::sendResetLink($request->only('email'));

        // Check if the reset link was sent successfully
        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        // Return a success response
        return response()->json([
            'status'  => 'success',
            'message' => 'Password reset link has been sent to your email address.',
        ], 200); // HTTP 200 OK
    }
}
