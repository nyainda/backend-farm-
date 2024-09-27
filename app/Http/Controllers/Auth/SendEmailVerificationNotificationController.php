<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SendEmailVerificationNotificationController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user = Auth::user();

        if ($user && $user->hasVerifiedEmail()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Email is already verified.',
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'status'  => 'success',
            'message' => 'Verification link has been sent.',
        ], 200);
    }
}
