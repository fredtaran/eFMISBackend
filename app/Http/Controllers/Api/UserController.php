<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view role', only: ['index']),
            new Middleware('permission:delete role', only: ['destroy'])
        ];
    }

    /**
     * Function: Retrieve all the users
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $users = User::with('roles')
                            ->with('division')
                            ->get();
            
            if ($users) {
                return ResponseHelper::success(message: "Successfully retrieved the users list.", data: $users, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve users list.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve users list. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve users list! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Save new user to the database
     * @param App\Http\Requests\UserRequest $request
     * @return responseJSON
     */
    public function store(UserRequest $request)
    {
        try {
            $user = User::create([
                "firstname"     => ucwords($request->firstname),
                "middlename"    => ucwords($request->middlename),
                "lastname"      => ucwords($request->lastname),
                "suffix"        => $request->suffix,
                "username"      => $request->username,
                "division_id"   => $request->division,
                "password"      => "DOHCHDNM"
            ]);

            if ($user) {
                $user->syncRoles($request->roles);

                return ResponseHelper::success(message: "Successfully save a new user.", data: $user, statusCode: 201);
            }
            
            return ResponseHelper::error("Unable to save user.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save user. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save user! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve a specific user
     * @param User $user
     * @return responseJSON
     */
    public function show(User $user)
    {
        try {
            $user = User::where('id', $user->id)
                        ->with(['roles', 'division'])
                        ->first();

            if ($user) {
                return ResponseHelper::success(message: "Successfully retrieved the specific user.", data: $user, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve specific user.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve specific user. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve specific user! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Update a specific user data
     * @param App\Http\Requests\UserUpdateRequest $request
     * @param User $user
     * @return responseJSON
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $user = User::findOrFail($user->id);

            if ($user) {
                $user->update([
                    "firstname"     => ucwords($request->firstname),
                    "middlename"    => ucwords($request->middlename),
                    "lastname"      => ucwords($request->lastname),
                    "suffix"        => $request->suffix,
                    "username"      => $request->username,
                    "division_id"   => $request->division
                ]);

                $user->syncRoles($request->roles);

                return ResponseHelper::success(message: "Successfully updated the specific user data.", data: $user, statusCode: 200);
            }

            return ResponseHelper::error("Unable to update specific user.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to update specific user. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to update specific user! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Delete a specific user
     * @param User $user
     * @return responseJSON
     */
    public function destroy(User $user)
    {
        try {
            $user = User::findOrFail($user->id);
            
            if ($user->delete()) {
                return ResponseHelper::success(message: "Successfully delete the specific user data.", data: [], statusCode: 200);
            }

            return ResponseHelper::error("Unable to delete specific user.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to delete specific user. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to delete specific user! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
