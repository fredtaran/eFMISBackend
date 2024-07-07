<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleUpdateRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RoleController extends Controller implements HasMiddleware
{
    /**
    * Get the middleware that should be assigned to the controller.
    */
    public static function middleware(): array
    {
        return [
            // new Middleware('permission:view role', only: ['index']),
            // new Middleware('permission:delete role', only: ['destroy'])
        ];
    }


    /**
     * Function: Retrieve all the roles list
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $roles = Role::all();

            if ($roles) {
                return ResponseHelper::success(message: "Successfully retrieved the list of roles.", data: $roles, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of roles", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of roles. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of roles! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: To store the newly added role to the database
     * @param App\Http\Requests\RoleRequest $request
     * @return responseJSON
     */
    public function store(RoleRequest $request)
    {
        try {
            $role = Role::create([
                'name' => $request->name
            ]);

            if ($role) {
                return ResponseHelper::success(message: "Successfully retrieved the list of role.", data: $role, statusCode: 201);
            }

            return ResponseHelper::error(message: "Unable to save new role! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save new role. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save new role! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve an individual role data
     * @param Role $role
     * @return responseJSON
     */
    public function show(Role $role)
    {
        try {
            $role = Role::findOrFail($role->id);

            if ($role) {
                return ResponseHelper::success(message: "Successfully retrieved a specific role.", data: $role, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to retrieve a specific role! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve a specific role. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve a specific role! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Update a specific role
     * @param App\Http\Requests\RoleUpdateRequest $request
     * @param Role $role
     * @return responseJSON
     */
    public function update(RoleUpdateRequest $request, Role $role)
    {
        try {
            $role = Role::findOrFail($role->id);
            
            if ($role) {
                $role->update([
                    'name'  => $request->name
                ]);

                return ResponseHelper::success(message: "Successfully updated a specific role.", data: $role, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to update a specific role! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to update a specific role. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to update a specific role! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Delete a specific role
     * @param Role $role
     * @return responseJSON
     */
    public function destroy(Role $role)
    {
        try {
            $role = Role::findOrFail($role->id);

            if ($role) {
                $role->delete();

                return ResponseHelper::success(message: "Successfully deleted a specific role.", data: [], statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to delete a specific role! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to delete a specific role. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to delete a specific role! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Give permissions to a role
     * @param Integer $role_id
     * @param Illuminate\Http\Request $request
     * @return responseJSON
     */
    public function givePermissionToRole(Request $request, $role_id)
    {
        try {
            $request->validate([
                'permissions'    => 'required'
            ]);

            $role = Role::findOrFail($role_id);

            if ($role) {
                $role->syncPermissions($request->permissions);

                return ResponseHelper::success(message: "Successfully given permissions to the specific role.", data: $role, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to give permissions to the specific role! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to give permission to the role. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to give permission to the role! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Get assigned permissions
     * @param Integer $role_id
     * @return responseJSON
     */
    public function getPermissionAssignedToRole($role_id)
    {
        try {
            $role = Role::findOrFail($role_id);

            if ($role) {
                $permissions = $role->permissions->pluck('id')->toArray();

                return ResponseHelper::success(message: "Successfully retrieved permissions assigned to the specific role.", data: $permissions, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to retrieve permission assigned to the specific role! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve permission assigned to the role. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve permission assigned to the role! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
