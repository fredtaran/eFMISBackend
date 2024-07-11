<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\FundSource;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\FundSourceRequest;
use App\Http\Requests\FundSourceUpdateRequest;

class FundSourceController extends Controller
{
    /**
    * Get the middleware that should be assigned to the controller.
    */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view fund source', only: ['index', 'show', 'byLineItem']),
            new Middleware('permission:update fund source', only: ['update']),
            new Middleware('permission:create fund source', only: ['store']),
            new Middleware('permission:delete fund source', only: ['destroy']),
        ];
    }

    /**
     * Function: Retrieve all the fund sources list
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $fundSources = FundSource::with(['lineItem'])->get();

            if ($fundSources) {
                return ResponseHelper::success(message: "Successfully retrieved the list of fund sources.", data: $fundSources, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of sections", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of sections. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of sections! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: To store the newly added fund source to the database
     * @param App\Http\Requests\FundSourceRequest $request
     * @return responseJSON
     */
    public function store(FundSourceRequest $request)
    {
        try {
            $fundSource = FundSource::create([
                'name'          => $request->name,
                'code'          => $request->code,
                'line_id'       => $request->line
            ]);

            if ($fundSource) {
                return ResponseHelper::success(message: "Successfully retrieved the list of fund source.", data: $fundSource, statusCode: 201);
            }

            return ResponseHelper::error(message: "Unable to save new fund source! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save new fund source. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save new fund source! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve an individual fund source data
     * @param FundSource $fundSource
     * @return responseJSON
     */
    public function show(FundSource $fundSource)
    {
        try {
            $fundSource = FundSource::findOrFail($fundSource->id);

            if ($fundSource) {
                return ResponseHelper::success(message: "Successfully retrieved a specific fund source.", data: $fundSource, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to retrieve a specific fund source! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve a specific fund source. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve a specific fund source! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve an fund source by line item
     * @param Integer $lineItem
     * @return responseJSON
     */
    public function byLineItem($lineItem)
    {
        try {
            $fundSource = FundSource::where('line_id', $lineItem)->get();

            if ($fundSource) {
                return ResponseHelper::success(message: "Successfully retrieved fund sources by line item.", data: $fundSource, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to retrieve fund sources by line item! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve fund sources by line item. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve fund sources by line item! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Update a specific fund source
     * @param App\Http\Requests\FundSourceUpdateRequest $request
     * @param FundSource $fundSource
     * @return responseJSON
     */
    public function update(FundSourceUpdateRequest $request, FundSource $fundSource)
    {
        try {
            $fundSource = FundSource::findOrFail($fundSource->id);
            
            if ($fundSource) {
                $fundSource->update([
                    'name'      => $request->name,
                    'code'      => $request->code,
                    'line_id'   => $request->line
                ]);

                return ResponseHelper::success(message: "Successfully updated a specific fund source.", data: $fundSource, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to update a specific fund source! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to update a specific fund source. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to update a specific fund source! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Delete a specific fund source
     * @param FundSource $fundSource
     * @return responseJSON
     */
    public function destroy(FundSource $fundSource)
    {
        try {
            $fundSource = FundSource::findOrFail($fundSource->id);

            if ($fundSource) {
                $fundSource->delete();

                return ResponseHelper::success(message: "Successfully deleted a specific fund source.", data: [], statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to delete a specific fund source! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to delete a specific fund source. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to delete a specific fund source! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
