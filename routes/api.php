<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\LineItemController;
use App\Http\Controllers\Api\PermissionController;

Route::controller(AuthController::class)->group(function () {
    /**
     * Route for logging in
     */
    Route::post('login', 'login');

    /**
     * Route for logging out
     */
    Route::get('logout', 'logout')->middleware(['auth:sanctum']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    /**
     * Routes: Resource routes for the permission controller
     */
    Route::apiResource('permissions', PermissionController::class);

    /**
     * Routes: Resource routes for the role controller
     */
    Route::apiResource('roles', RoleController::class);
    Route::put('roles/{role_id}/give-permissions', [RoleController::class, 'givePermissionToRole']);
    Route::get('roles/{role_id}/get-permissions', [RoleController::class, 'getPermissionAssignedToRole']);

    /**
     * Routes: Resource routes for the user controller
     */
    Route::apiResource('users', UserController::class);

    /**
     * Routes: Resource routes for the division controller
     */
    Route::apiResource('divisions', DivisionController::class);

    /**
     * Routes: Resource routes for the line item controller
     */
    Route::apiResource('line-items', LineItemController::class);
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
