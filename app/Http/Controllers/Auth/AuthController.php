<?php
namespace App\Http\Controllers\Auth;

use App\Actions\Users\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request, CreateUser $createUser): JsonResponse
    {
        try {
            $user = $createUser(
                name: $request->input('name'),
                email: $request->input('email'),
                password: $request->input('password'),
            );

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => new UserResource($user)
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('User registration failed', $e->getMessage());
        }
    }

    /**
     * Authenticate a user and return a token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->errorResponse('Invalid credentials', null, Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            return $this->errorResponse('Could not create token', $e->getMessage());
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh a token.
     */
    public function refresh(): JsonResponse
    {
        try {
            $token = JWTAuth::refresh();
            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            return $this->errorResponse('Token refresh failed', $e->getMessage());
        }
    }

    /**
     * Get the authenticated User.
     */
    public function me(): JsonResponse
    {
        return response()->json(new UserResource(Auth::user()));
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60, // Adjust based on your JWT configuration
            'user' => new UserResource(Auth::user())
        ]);
    }

    /**
     * Return an error response.
     */
    protected function errorResponse(string $message, ?string $error = null, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if ($error) {
            $response['error'] = $error;
        }

        return response()->json($response, $statusCode);
    }
}
