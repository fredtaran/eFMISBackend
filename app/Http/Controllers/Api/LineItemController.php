<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\LineItem;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\LineItemRequest;
use App\Http\Requests\LineItemUpdateRequest;

class LineItemController extends Controller
{
    /**
     * Function: Retrieve all the line item list
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $lineItems = LineItem::all();

            if ($lineItems) {
                return ResponseHelper::success(message: "Successfully retrieved the list of line items.", data: $lineItems, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of line items", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of line items. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of line items! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: To store the newly added line item to the database
     * @param App\Http\Requests\LineItemRequest $request
     * @return responseJSON
     */
    public function store(LineItemRequest $request)
    {
        try {
            $lineItem = LineItem::create([
                'name'  => $request->name,
                'code'  => $request->code
            ]);

            if ($lineItem) {
                return ResponseHelper::success(message: "Successfully save a new line item.", data: $lineItem, statusCode: 201);
            }

            return ResponseHelper::error(message: "Unable to save new line item! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save new line item. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save new line item! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve an individual line item data
     * @param LineItem $lineItem
     * @return responseJSON
     */
    public function show(LineItem $lineItem)
    {
        try {
            $lineItem = LineItem::findOrFail($lineItem->id);

            if ($lineItem) {
                return ResponseHelper::success(message: "Successfully retrieved a specific line item.", data: $lineItem, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to retrieve a specific line item! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve a specific line item. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve a specific line item! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Update a specific line item
     * @param App\Http\Requests\LineItemUpdateRequest $request
     * @param LineItem $lineItem
     * @return responseJSON
     */
    public function update(LineItemUpdateRequest $request, LineItem $lineItem)
    {
        try {
            $lineItem = LineItem::findOrFail($lineItem->id);
            
            if ($lineItem) {
                $lineItem->update([
                    'name'  => $request->name,
                    'code'  => $request->code
                ]);

                return ResponseHelper::success(message: "Successfully updated a specific line item.", data: $lineItem, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to update a specific line item! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to update a specific line item. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to update a specific line item! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Delete a specific line item
     * @param LineItem $lineItem
     * @return responseJSON
     */
    public function destroy(LineItem $lineItem)
    {
        try {
            $lineItem = LineItem::findOrFail($lineItem->id);

            if ($lineItem) {
                $lineItem->delete();

                return ResponseHelper::success(message: "Successfully deleted a specific line item.", data: [], statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to delete a specific line item! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to delete a specific line item. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to delete a specific line item! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
