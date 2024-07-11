<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Allocation;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\AllocationRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AllocationController extends Controller implements HasMiddleware
{
    /**
    * Get the middleware that should be assigned to the controller.
    */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view allocation', only: ['index']),
            // new Middleware('permission:update allocation', only: ['update']),
            new Middleware('permission:create allocation', only: ['store']),
            // new Middleware('permission:delete allocation', only: ['destroy']),
        ];
    }

    /**
     * Function: Retrieve all the allocation list
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $allocations = Allocation::with(['lineItem', 'fundSource'])->get();

            if ($allocations) {
                return ResponseHelper::success(message: "Successfully retrieved the list of allocations.", data: $allocations, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of allocations", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of allocations. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of allocations! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve allocation base of line item and fund source
     * @param Integer $lineItem
     * @param Integer $fundSource
     * @return responseJSON
     */
    public function byLineAndFund($lineItem, $fundSource)
    {
        try {
            $allocations = Allocation::where('line_id', $lineItem)
                                    ->where('fs_id', $fundSource)
                                    ->with(['lineItem', 'fundSource'])
                                    ->get();

            if ($allocations) {
                return ResponseHelper::success(message: "Successfully retrieved the list of allocations.", data: $allocations, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of allocations", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of allocations. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of allocations! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: To store the newly added allocation to the database
     * @param App\Http\Requests\AllocationRequest $request
     * @return responseJSON
     */
    public function store(AllocationRequest $request)
    {
        try {
            $allocation = Allocation::create([
                'program'       => $request->program,
                'code'          => $request->code,
                'amount'        => $request->amount,
                'year'          => date('Y'),
                'line_id'       => $request->line,
                'fs_id'         => $request->fundSource,
                'section_id'    => $request->section
            ]);

            if ($allocation) {
                return ResponseHelper::success(message: "Successfully saved new allocation.", data: $allocation, statusCode: 201);
            }

            return ResponseHelper::error(message: "Unable to save new allocation! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save new allocation. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save new allocation! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
