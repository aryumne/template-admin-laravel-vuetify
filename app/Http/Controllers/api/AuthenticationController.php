<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    protected $sessionLifetime;

    function __construct()
    {
        $this->sessionLifetime = env('SESSION_LIFETIME', 120);
    }
    public function me(Request $request)
    {
        return HttpHelper::successResponse('User credential', $request->user(), Response::HTTP_OK);
    }
    public function signIn(Request $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return HttpHelper::errorResponse('The credentials is not valid', [], Response::HTTP_UNAUTHORIZED);
        }
        $user = $request->user();
        $expired = Carbon::now()->addMinutes($this->sessionLifetime);
        $token = $user->createToken($user->id, [], $expired)->plainTextToken;
        $response = [
            'user' => new UserResource($user),
            'token' => $token
        ];
        return HttpHelper::successResponse('successfully logged in', $response, Response::HTTP_OK);
    }

    public function signOut(Request $request)
    {
        $request->user()->tokens()->delete();
        return HttpHelper::successResponse('successfully logged out', [], Response::HTTP_OK);
    }
}
