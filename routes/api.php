<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UacsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\LineItemController;
use App\Http\Controllers\Api\AllocationController;
use App\Http\Controllers\Api\FundSourceController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\PurchaseDetailsController;

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
     * Routes: Resource routes for the section controller
     */
    Route::apiResource('sections', SectionController::class);
    Route::get('sections-by-division/{divisionId}', [SectionController::class, 'getSectionByDivision']);

    /**
     * Routes: Resource routes for the line item controller
     */
    Route::apiResource('line-items', LineItemController::class);
    
    /**
     * Routes: Resource routes for the fund source controller
     */
    Route::apiResource('fund-sources', FundSourceController::class);
    Route::get('fund-sources/by-line-item/{lineItem}', [FundSourceController::class, 'byLineItem']);

    /**
     * Routes: Resource routes for the uacs controller
     */
    Route::apiResource('uacs', UacsController::class);
    Route::post('uacs-bulk', [UacsController::class, 'storeInBulk']);

    /**
     * Routes: Resouce routes for the log controller
     */
    Route::apiResource('logs', LogController::class);
    Route::post('logs/{transactionId}', [LogController::class, 'store']);

    /**
     * Routes: Resource routes for the purchase detail controller
     */
    Route::apiResource('purchase-details', PurchaseDetailsController::class);
    Route::get('purchase-details/by-user/{userId}', [PurchaseDetailsController::class, 'prByUser']);
    Route::get('purchase-details/owned-and-forwarded/{userId}', [PurchaseDetailsController::class, 'ownedAndForwarded']);
    
    /**
     * Routes: Resource routes for the transaction controller
     */
    Route::apiResource('transactions', TransactionController::class);
    Route::put('forward-transaction/{transactionId}', [TransactionController::class, 'forwardTransaction']);
    Route::put('receive-transaction/{transactionId}', [TransactionController::class, 'receiveTransaction']);
    Route::put('retract-transaction/{transactionId}', [TransactionController::class, 'rectractTransaction']);
    Route::put('update-transaction/{transactionId}', [TransactionController::class, 'updateTransaction']);

    /**
     * Routes: Resource routes for the allocation controller
     */
    Route::apiResource('allocations', AllocationController::class);
    Route::get('allocations/by-line-and-fund/{lineItem}/{fundSource}', [AllocationController::class, 'byLineAndFund']);
});