<?php
namespace App\Http\Controllers;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use App\Models\User;

/**
 * @group Authentication
 *
 * API endpoints for managing authentication
 */
class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @response {
     *  "access_token": "eyJ0eXA...",
     *  "token_type": "Bearer",
     *  "expires_in": "2023-06-15T12:39:21.898199Z",
     *   "user": {
     *   "id": 1,
     *   "name": "Test",
     *   "email": "test@test.com",
     *   "total_amount": "10000000.00",
     *   "created_at": null,
     *   "updated_at": null
     *   }
     *
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (! $token = auth()->attempt($request->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @response {
     *  "message": "User successfully registered",
     *  "user": {
     *   "name": "Test",
     *   "email": "test1@test.com",
     *   "updated_at": "2023-06-15T12:04:26.000000Z",
     *   "created_at": "2023-06-15T12:04:26.000000Z",
     *   "id": 2
     *  }
     * }
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = array_merge($request->validated(), ['password' => bcrypt($request->get('password'))]);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => User::create($data)
        ], 201);
    }

    /**
     * Log the user out.
     *
     * @response {
     *  "message": "User successfully signed out",
     * }
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @response {
     *  "access_token": "eyJ0eXA...",
     *  "token_type": "Bearer",
     *  "expires_in": "2023-06-15T12:39:21.898199Z",
     *   "user": {
     *   "id": 1,
     *   "name": "Test",
     *   "email": "test@test.com",
     *   "total_amount": "10000000.00",
     *   "created_at": null,
     *   "updated_at": null
     *   }
     *
     * }
     */
    public function refresh(): JsonResponse
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @response {
     *   "id": 1,
     *   "name": "Test",
     *   "email": "test@test.com",
     *   "total_amount": "10000000.00",
     *   "created_at": null,
     *   "updated_at": null
     *   }
     */
    public function userProfile(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     */
    protected function createNewToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => now()->addMinutes(60),
            'user' => auth()->user()
        ]);
    }
}
