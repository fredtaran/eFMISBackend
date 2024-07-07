<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\DivisionRequest;
use App\Http\Requests\DivisionUpdateRequest;

class DivisionController extends Controller
{
    /**
     * Function: Retrieve all the divisions list
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $divisions = Division::all();

            if ($divisions) {
                return ResponseHelper::success(message: "Successfully retrieved the list of divisions.", data: $divisions, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of divisions", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of divisions. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of divisions! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: To store the newly added division to the database
     * @param App\Http\Requests\DivisionRequest $request
     * @return responseJSON
     */
    public function store(DivisionRequest $request)
    {
        try {
            $division = Division::create([
                'name'      => $request->name,
                'shorthand' => $request->shorthand
            ]);

            if ($division) {
                return ResponseHelper::success(message: "Successfully save a new division.", data: $division, statusCode: 201);
            }

            return ResponseHelper::error(message: "Unable to save new division! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save new division. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save new division! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve an individual division data
     * @param Division $division
     * @return responseJSON
     */
    public function show(Division $division)
    {
        try {
            $division = Division::findOrFail($division->id);

            if ($division) {
                return ResponseHelper::success(message: "Successfully retrieved a specific permission.", data: $division, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to retrieve a specific division! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve a specific division. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve a specific division! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Update a specific permission
     * @param App\Http\Requests\DivisionUpdateRequest $request
     * @param Division $division
     * @return responseJSON
     */
    public function update(DivisionUpdateRequest $request, Division $division)
    {
        try {
            $division = Division::findOrFail($division->id);
            
            if ($division) {
                $division->update([
                    'name'  => $request->name
                ]);

                return ResponseHelper::success(message: "Successfully updated a specific division.", data: $division, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to update a specific division! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to update a specific division. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to update a specific division! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Delete a specific division
     * @param Division $division
     * @return responseJSON
     */
    public function destroy(Division $division)
    {
        try {
            $division = Division::findOrFail($division->id);

            if ($division) {
                $division->delete();

                return ResponseHelper::success(message: "Successfully deleted a specific division.", data: [], statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to delete a specific division! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to delete a specific division. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to delete a specific division! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
