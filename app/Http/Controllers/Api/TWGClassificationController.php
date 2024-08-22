<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Requests\TWGRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\TwgClassification;

class TWGClassificationController extends Controller
{
     /**
     * Function: Retrieve all the TWG Classification list
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $twgClass = TwgClassification::all();

            if ($twgClass) {
                return ResponseHelper::success(message: "Successfully retrieved the list of twg classification.", data: $twgClass, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of twg classification.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of twg classification.. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of twg classification.! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: To store the newly added TWG Classification to the database
     * @param App\Http\Requests\TWGRequest $request
     * @return responseJSON
     */
    public function store(TWGRequest $request)
    {
        try {
            $twgClass = TwgClassification::create([
                'twg_title'  => $request->twg_title,
            ]);

            if ($twgClass = true) {
                return ResponseHelper::success(message: "Successfully saved a new TWG Classification.", data: $request->all(), statusCode: 201);
            }

            return ResponseHelper::error(message: "Unable to save new TWG Classification! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save new TWG Classification. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save new TWG Classification! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
