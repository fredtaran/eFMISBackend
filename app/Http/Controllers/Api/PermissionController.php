<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Http\Requests\PermissionUpdateRequest;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Function: Retrieve all the permissions list
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $permissions = Permission::all();

            if ($permissions) {
                return ResponseHelper::success(message: "Successfully retrieved the list of permissions.", data: $permissions, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of permissions", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of permissions. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of permissions! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: To store the newly added permission to the database
     * @param App\Http\Requests\PermissionRequest $request
     * @return responseJSON
     */
    public function store(PermissionRequest $request)
    {
        try {
            $permission = Permission::create([
                'name' => $request->name
            ]);

            if ($permission) {
                return ResponseHelper::success(message: "Successfully saved the a new permission.", data: $permission, statusCode: 201);
            }

            return ResponseHelper::error(message: "Unable to save new permission! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save new permission. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save new permission! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve an individual permission data
     * @param Permission $permission
     * @return responseJSON
     */
    public function show(Permission $permission)
    {
        try {
            $permission = Permission::findOrFail($permission->id);

            if ($permission) {
                return ResponseHelper::success(message: "Successfully retrieved a specific permission.", data: $permission, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to retrieve a specific permission! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve a specific permission. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve a specific permission! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Update a specific permission
     * @param App\Http\Requests\PermissionUpdateRequest $request
     * @param Permission $permission
     * @return responseJSON
     */
    public function update(PermissionUpdateRequest $request, Permission $permission)
    {
        try {
            $permission = Permission::findOrFail($permission->id);
            
            if ($permission) {
                $permission->update([
                    'name'  => $request->name
                ]);

                return ResponseHelper::success(message: "Successfully updated a specific permission.", data: $permission, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to update a specific permission! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to update a specific permission. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to update a specific permission! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Delete a specific permission
     * @param Permission $permission
     * @return responseJSON
     */
    public function destroy(Permission $permission)
    {
        try {
            $permission = Permission::findOrFail($permission->id);

            if ($permission) {
                $permission->delete();

                return ResponseHelper::success(message: "Successfully deleted a specific permission.", data: [], statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to delete a specific permission! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to delete a specific permission. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to delete a specific permission! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
