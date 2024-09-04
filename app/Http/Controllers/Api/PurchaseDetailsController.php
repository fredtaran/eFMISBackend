<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\PurchaseDetail;
use App\Models\PurchaseDetails;
use App\Models\Log as ActivityLog;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseDetailRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PurchaseDetailsController extends Controller implements HasMiddleware
{
    /**
    * Get the middleware that should be assigned to the controller.
    */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view purchase_detail', only: ['index', 'prByUser', 'ownedAndForwarded']),
            new Middleware('permission:update purchase_detail', only: []),
            new Middleware('permission:create purchase_detail', only: ['store']),
            new Middleware('permission:delete purchase_detail', only: []),
        ];
    }

    /**
     * Function: Retrieve all the purchase list
     * @param NA
     * @return responseJSON
     */
    public function index()
    {
        try {
            $purchaseDetails = PurchaseDetail::with('transaction')->get();

            if ($purchaseDetails) {
                return ResponseHelper::success(message: "Successfully retrieved the list of purchase.", data: $purchaseDetails, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of purchase", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of purchase. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of purchase! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve all the purchase by user
     * @param Integer $userId
     * @return responseJSON
     */
    public function prByUser($userId)
    {
        try {
            $transactions = Transaction::where('creator', $userId)
                                        ->with(['purchaseDetails'])
                                        ->orderBy('received', 'DESC')
                                        ->orderBy('created_at', 'DESC')
                                        ->get();

            if ($transactions) {
                return ResponseHelper::success(message: "Successfully retrieved the list of transactions.", data: $transactions, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of transactions", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of transactions. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of purchase! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve all the purchase forwarded to $userId or created by $userId
     * @param Integer $userId
     * @return responseJSON
     */
    public function ownedAndForwarded($userId)
    {
        try {
            $transactions = Transaction::query()
                                        ->where('received', false)
                                        ->where('creator', $userId)
                                        ->orWhere('to', $userId)
                                        ->orWhere('from', $userId)
                                        ->with(['purchaseDetails'])
                                        ->orderBy('received', 'ASC')
                                        ->orderBy('created_at', 'DESC')
                                        ->get();

            if ($transactions) {
                return ResponseHelper::success(message: "Successfully retrieved the list of transaction.", data: $transactions, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve list of transaction", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of transaction. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of transaction! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Save new purchase request to the database
     * @param App\Http\Requests\PurchaseDetailRequest $request
     * @return responseJSON
     */
    public function store(PurchaseDetailRequest $request)
    {
        try {
            $transactionDetails = Transaction::create([
                'creator'           => Auth::user()->id,
                'is_pr'             => 1,
                'from'              => Auth::user()->id,
                'to'                => Auth::user()->id,
                'received'          => 1,
                'reference_no'      => $request->reference_no,
                'activity_title'    => $request->activity_title
            ]);

            if ($transactionDetails) {
                $purchaseDetails = PurchaseDetail::create([
                    'amount'            => $request->amount,
                    'transaction_id'    => $transactionDetails->id
                ]);

                $name = Auth::user()->firstname . " " . Auth::user()->lastname;
                
                ActivityLog::create([
                    'is_transaction'    => 1,
                    'transaction_id'    => $transactionDetails->id,
                    'from'              => Auth::user()->id,
                    'activity'          => "$name added a new transaction with reference number: $request->reference_no"
                ]);

                return ResponseHelper::success(message: "Successfully save a new transaction.", data: $transactionDetails, statusCode: 201);
            }
            
            return ResponseHelper::error("Unable to save transaction.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to save transaction. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save transaction! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve all the purchase list
     * @param NA
     * @return responseJSON
     */
    public function show($purchaseDetails)
    {
        try {
            dd("Hellow");
            // $purchaseDetails = PurchaseDetails::with('transaction')->get();

            // if ($purchaseDetails) {
            //     return ResponseHelper::success(message: "Successfully retrieved the list of purchase.", data: $purchaseDetails, statusCode: 200);
            // }

            // return ResponseHelper::error("Unable to retrieve list of purchase", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve list of purchase. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve list of purchase! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
