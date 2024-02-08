<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HttpHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthenticationWebController extends Controller
{
    protected $emailAdmin;

    function __construct()
    {
        $this->emailAdmin  = env('ACCOUNT_EMAIL');
    }
    public function signIn(Request $req): JsonResponse
    {
        try {
            if (!Auth::attempt(["email" => $this->emailAdmin, "password" => $req->password])) {
                return HttpHelper::errorResponse('Password salah!', [], Response::HTTP_UNAUTHORIZED);
            }
            $user = User::where('email', $req->email)->first();

            $log = $req->session()->regenerate();
            if ($log === false) throw new Exception('The generate session is not completed');
            Log::info("Sign in", ['data' => $user]);

            return HttpHelper::successResponse('Login berhasil, harap tunggu sedang memuat halaman kasir...', [], Response::HTTP_OK);
        } catch (Exception $e) {
            Auth::logout();
            Log::error("Sign in", ['error_msg' => $e->getMessage(), 'detail' => $e]);
            return HttpHelper::errorResponse('Authentication Failed', $e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }

    public function signOut(Request $request): JsonResponse
    {
        try {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return HttpHelper::successResponse('Logout berhasil.', [], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error("Sign out", ['error_msg' => $e->getMessage(), 'detail' => $e]);
            return HttpHelper::errorResponse('Authentication Failed', $e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
    }
}
