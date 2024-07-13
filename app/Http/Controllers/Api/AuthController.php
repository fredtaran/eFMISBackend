<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Function: Authenticate the user credentials
     * @param App\Http\Requests\LoginRequest $request
     * @return responseJSON
     */
    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password])) {
                return ResponseHelper::error(message: "Incorrect credentials. Please provide a valid username and password.", statusCode: 401);
            }

            $user = User::where('id', Auth::user()->id)->with('roles')->first();

            if ($user->active) {
                // Create token
                $token = $user->createToken('token')->plainTextToken;
                
                return response()->json([
                    'message'   => 'Logged in',
                    'user'      => $user,
                    'token'     => $token
                ], 200);
            }

            return ResponseHelper::error(message: "Your account is currently deactivated. Please contact support to reactivate it.", statusCode: 403);
        } catch (Exception $e) {
            Log::error("Unable to login: " . $e->getMessage() . " - Line No. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to login! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Logout user and remove the token
     * @param
     * @return responseJSON
     */
    public function logout()
    {
        try {
            $user = Auth::user();

            if ($user) { 
                $user->currentAccessToken()->delete();

                return ResponseHelper::success(message: "User successfully logged out.", data: [], statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to logout user.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to logout user: " . $e->getMessage() . " - Line No. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to logout user! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
