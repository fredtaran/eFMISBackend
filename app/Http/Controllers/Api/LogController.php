<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\Log as ActivityLog;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class LogController extends Controller implements HasMiddleware
{
    /**
    * Get the middleware that should be assigned to the controller.
    */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view log', only: ['index']),
            new Middleware('permission:update log', only: ['update']),
            new Middleware('permission:create log', only: ['store']),
            new Middleware('permission:delete log', only: ['destroy']),
        ];
    }

    /**
     * Function: Retrieve all the logs by transaction id
     * @param $transactionId
     * @return responseJSON
     */
    public function show($transactionId)
    {
        try {
            $logs = ActivityLog::with(['sender', 'receiver', 'transaction'])
                                ->where('transaction_id', $transactionId)
                                ->orderBy('created_at', 'DESC')
                                ->get();

            if ($logs) {
                return ResponseHelper::success(message: "Successfully retrieved activity log.", data: $logs, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve activity log", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve activity log. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve activity log! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Add a status to the activity log
     * @param App\Http\Requests\StatusRequest $request
     * @param Integer $transactionId
     * @return responseJSON
     */
    public function store(StatusRequest $request, $transactionId)
    {
        try {
            $authUser = Auth::user()->firstname . " " . Auth::user()->lastname;

            $log = ActivityLog::create([
                'is_transaction'    => true,
                'transaction_id'    => $transactionId,
                'from'              => Auth::user()->id,
                'activity'          => "$authUser added a status to the transaction log.",
                'additional_notes'  => $request->status
            ]);

            if ($log) {
                return ResponseHelper::success(message: "Successfully save status to the activity log.", data: $log, statusCode: 201);
            }

            return ResponseHelper::error("Unable to save", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save status. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save status! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
