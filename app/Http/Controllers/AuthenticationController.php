<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function  login(LoginRequest  $request): JsonResponse
    {
        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                /**
                 * @var User $user
                 */
                $user = Auth::user();
                $token = $user->createToken('api-token')->plainTextToken;
                return $this->respondSuccess(['token' => $token, 'user' => new UserResource($user)], 'Login successful');
            }
            return $this->respondUnAuthorized('invalid email or password');
        } catch (\Exception $exception) {
            return $this->respondInternalError($exception->getMessage());
        }
    }
}
