<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticationWebController extends Controller
{
    public function signIn(Request $req): JsonResponse
    {
        try {
            if (!Auth::attempt($req->only(['email', 'password']))) {
                return HttpHelper::errorResponse('The credentials is not valid', [], Response::HTTP_UNAUTHORIZED);
            }
            $user = User::where('email', $req->email)->first();
            // Check if the user's account has expired
            // if ($user->profile->expired_date !== null && now()->greaterThanOrEqualTo($user->profile->expired_date)) {
            //     Auth::logout(); // Log the user out
            //     return HttpHelper::errorResponse('Your account has expired', [], Response::HTTP_UNAUTHORIZED);
            // }

            $req->session()->regenerate();
            
            return HttpHelper::successResponse('Successfully logged in', [], Response::HTTP_OK);
        } catch (Exception $e) {
            Auth::logout();
            return HttpHelper::errorResponse('Authentication Failed', $e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function signOut(Request $request): JsonResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return HttpHelper::successResponse('successfully logged out', [], Response::HTTP_OK);
    }
}
