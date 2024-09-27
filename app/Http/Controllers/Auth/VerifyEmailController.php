<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {
        // Check if the email has already been verified
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email is already verified.',
            ], 400); // HTTP 400 Bad Request
        }

        // Mark the email as verified and fire the Verified event
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            return response()->json([
                'status'  => 'success',
                'message' => 'Email has been successfully verified.',
            ], 200); // HTTP 200 OK
        }

        // Fallback response if the verification fails
        return response()->json([
            'status'  => 'error',
            'message' => 'Failed to verify email.',
        ], 500); // HTTP 500 Internal Server Error
    }
}
