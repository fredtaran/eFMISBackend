<?php

namespace App\Http\Controllers\Api;

use Excel;
use Exception;
use App\Models\Uacs;
use App\Imports\UacsImport;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Requests\UacsRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\UacsBulkRequest;
use App\Http\Requests\UacsUpdateRequest;

class UacsController extends Controller
{
    /**
    * Get the middleware that should be assigned to the controller.
    */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view uacs', only: ['index', 'show']),
            new Middleware('permission:update uacs', only: ['update']),
            new Middleware('permission:create uacs', only: ['store']),
            new Middleware('permission:delete uacs', only: ['destroy']),
        ];
    }

    /**
     * Function: Retrieve all the uacs list
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $uacs = Uacs::all();

            if ($uacs) {
                return ResponseHelper::success(message: "Successfully retrieved the list of uacs.", data: $uacs, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of uacs", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of uacs. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of uacs! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: To store the newly added uacs to the database
     * @param App\Http\Requests\UacsRequest $request
     * @return responseJSON
     */
    public function store(UacsRequest $request)
    {
        try {
            $uacs = Uacs::create([
                'title'    => $request->code,
                'code'     => $request->title
            ]);

            if ($uacs) {
                return ResponseHelper::success(message: "Successfully retrieved the list of uacs.", data: $uacs, statusCode: 201);
            }

            return ResponseHelper::error(message: "Unable to save new uacs! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save new uacs. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save new uacs! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Store new uacs by bulk
     * @param App\Http\Requests\UacsBulkRequest $request
     * @return responseJSON
     */
    public function storeInBulk(UacsBulkRequest $request)
    {
        try {
            $originalRowCount = Uacs::all()->count();
            $import = new UacsImport;
            $success = Excel::import($import, $request->uacs_file);
            
            return ResponseHelper::success(message: "Successfully retrieved the list of uacs.", data: [], statusCode: 201);

            // return ResponseHelper::error(message: "Unable to save new uacs! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save bulk uacs. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save bulk uacs! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve an individual uacs data
     * @param Uacs $uac
     * @return responseJSON
     */
    public function show(Uacs $uac)
    {
        try {
            $uacs = Uacs::findOrFail($uac->id);

            if ($uacs) {
                return ResponseHelper::success(message: "Successfully retrieved a specific uacs.", data: $uacs, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to retrieve a specific uacs! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve a specific uacs. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve a specific uacs! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Update a specific uacs
     * @param App\Http\Requests\UacsUpdateRequest $request
     * @param Uacs $uac
     * @return responseJSON
     */
    public function update(UacsUpdateRequest $request, Uacs $uac)
    {
        try {
            $uacs = Uacs::findOrFail($uac->id);
            
            if ($uacs) {
                $uacs->update([
                    'title'    => $request->title,
                    'code'     => $request->code
                ]);

                return ResponseHelper::success(message: "Successfully updated a specific uacs.", data: $uacs, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to update a specific uacs! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to update a specific uacs. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to update a specific uacs! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    // /**
    //  * Function: Delete a specific section
    //  * @param Section $section
    //  * @return responseJSON
    //  */
    // public function destroy(Section $section)
    // {
    //     try {
    //         $section = Section::findOrFail($section->id);

    //         if ($section) {
    //             $section->delete();

    //             return ResponseHelper::success(message: "Successfully deleted a specific section.", data: [], statusCode: 200);
    //         }

    //         return ResponseHelper::error(message: "Unable to delete a specific section! Try again.", statusCode: 500);
    //     } catch (Exception $e) {
    //         Log::error("Unable to delete a specific section. : " . $e->getMessage() . " - Line no. " . $e->getLine());
    //         return ResponseHelper::error(message: "Unable to delete a specific section! Try again. " . $e->getMessage(), statusCode: 500);
    //     }
    // }
}
