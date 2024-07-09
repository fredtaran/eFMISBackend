<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{
    /**
    * Get the middleware that should be assigned to the controller.
    */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view section', only: ['index', 'show']),
            new Middleware('permission:update section', only: ['update']),
            new Middleware('permission:create section', only: ['store']),
            new Middleware('permission:delete section', only: ['destroy']),
        ];
    }

    /**
     * Function: Retrieve all the sections list
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $sections = Section::with(['division'])->get();

            if ($sections) {
                return ResponseHelper::success(message: "Successfully retrieved the list of sections.", data: $sections, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of sections", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of sections. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of sections! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: To store the newly added section to the database
     * @param App\Http\Requests\SectionRequest $request
     * @return responseJSON
     */
    public function store(SectionRequest $request)
    {
        try {
            $section = Section::create([
                'name'          => $request->name,
                'shorthand'     => $request->shorthand,
                'division_id'   => $request->division
            ]);

            if ($section) {
                return ResponseHelper::success(message: "Successfully retrieved the list of section.", data: $section, statusCode: 201);
            }

            return ResponseHelper::error(message: "Unable to save new section! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save new section. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save new section! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve an individual section data
     * @param Section $section
     * @return responseJSON
     */
    public function show(Section $section)
    {
        try {
            $section = Section::findOrFail($section->id);

            if ($section) {
                return ResponseHelper::success(message: "Successfully retrieved a specific section.", data: $section, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to retrieve a specific section! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve a specific section. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve a specific section! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Update a specific section
     * @param App\Http\Requests\SectionUpdateRequest $request
     * @param Section $section
     * @return responseJSON
     */
    public function update(SectionUpdateRequest $request, Section $section)
    {
        try {
            $section = Section::findOrFail($section->id);
            
            if ($section) {
                $section->update([
                    'name'          => $request->name,
                    'shorthand'     => $request->shorthand,
                    'division_id'   => $request->division
                ]);

                return ResponseHelper::success(message: "Successfully updated a specific section.", data: $section, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to update a specific section! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to update a specific section. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to update a specific section! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Delete a specific section
     * @param Section $section
     * @return responseJSON
     */
    public function destroy(Section $section)
    {
        try {
            $section = Section::findOrFail($section->id);

            if ($section) {
                $section->delete();

                return ResponseHelper::success(message: "Successfully deleted a specific section.", data: [], statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to delete a specific section! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to delete a specific section. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to delete a specific section! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
