<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use PDF;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\PurchaseDetail;
use App\Models\UacsTransaction;
use App\Models\Log as ActivityLog;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TransactionRequest;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Requests\PrTransactionUpdateRequest;
use Illuminate\Routing\Controllers\HasMiddleware;

class TransactionController extends Controller implements HasMiddleware
{
    /**
    * Get the middleware that should be assigned to the controller.
    */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view transaction', only: ['index', 'show']),
            new Middleware('permission:update transaction', only: ['forwardTransaction', 'receiveTransaction', 'rectractTransaction']),
            new Middleware('permission:create transaction', only: ['store']),
            new Middleware('permission:delete transaction', only: ['']),
        ];
    }

    /**
     * Function: Forward transaction
     * @param Illuminate\Http\Request $request
     * @param Integer $transactionId
     * @return responseJSON
     */
    public function forwardTransaction(Request $request, $transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);
            
            if ($transaction) {
                $updateTransaction = $transaction->update([
                    'from'      => Auth::user()->id,
                    'to'        => $request->query('receiver'),
                    'received'  => false
                ]);

                $to = User::where('id', $request->query('receiver'))->first();
                
                $sender = Auth::user()->firstname . " " . Auth::user()->lastname;
                $receiver = $to->firstname . " " . $to->lastname;

                if ($updateTransaction) {
                    ActivityLog::create([
                        'is_transaction'    => 1,
                        'transaction_id'    => $transactionId,
                        'from'              => Auth::user()->id,
                        'to'                => $request->query('receiver'),
                        'activity'          => "$sender forwarded a transaction with reference number: $transaction->reference_no to $receiver.",
                        'additional_notes'  => $request->input('notes')
                    ]);
                    
                    return ResponseHelper::success(message: "Registry successfully forwarded.", data: [], statusCode: 200);
                }
            }

            return ResponseHelper::error(message: "Unable to forward registry! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to forward registry. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to forward registry! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Receive transaction
     * @param $transactionId
     * @return responseJSON
     */
    public function receiveTransaction($transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);

            if ($transaction) {
                $updateTransaction = $transaction->update([
                    'from'      => Auth::user()->id,
                    'received'  => true
                ]);

                $receiver = Auth::user()->firstname . " " . Auth::user()->lastname;
                
                if ($updateTransaction) {
                    ActivityLog::create([
                        'is_transaction'    => 1,
                        'transaction_id'    => $transactionId,
                        'from'              => Auth::user()->id,
                        'activity'          => "$receiver received a transaction with reference number: $transaction->reference_no."
                    ]);
                    
                    return ResponseHelper::success(message: "Transaction successfully received.", data: $transaction, statusCode: 200);
                }
            }

            return ResponseHelper::error(message: "Unable to receive transaction! Try again.", statusCode: 500);
        } catch  (Exception $e) {
            Log::error("Unable to receive transaction. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to receive transaction! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retract transaction
     * @param $transactionId
     * @return responseJSON
     */
    public function rectractTransaction($transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);

            if ($transaction) {
                $updateTransaction = $transaction->update([
                    'to'        => Auth::user()->id,
                    'received'  => true
                ]);

                $receiver = Auth::user()->firstname . " " . Auth::user()->lastname;

                if ($updateTransaction) {
                    ActivityLog::create([
                        'is_transaction'    => 1,
                        'transaction_id'    => $transactionId,
                        'from'              => Auth::user()->id,
                        'activity'          => "$receiver has retracted the transaction with reference number: $transaction->reference_no."
                    ]);
                    
                    return ResponseHelper::success(message: "Transaction successfully received.", data: $transaction, statusCode: 200);
                }
            }

            return ResponseHelper::error(message: "Unable to retract transaction! Try again.", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retract transaction. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retract transaction! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve a specific transaction
     * @param Integer $transactionId
     * @return responseJSON
     */
    public function show($transactionId)
    {
        try {
            $transactionDetail = Transaction::where('id', $transactionId)
                                            ->with(['purchaseDetails', 'allocation', 'accounts.uacs'])
                                            ->first();

            if ($transactionDetail) {
                return ResponseHelper::success(message: "Successfully retrieved transaction detail.", data: $transactionDetail, statusCode: 200);
            }

            return ResponseHelper::error("Unable to retrieve transaction detail", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieve transaction detail. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve transaction detail! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Update validation
     * @param App\Http\Requests\PrTransactionUpdateRequest $request
     * @return responseJSON
     */
    public function updateTransaction(PrTransactionUpdateRequest $request, $transactionId)
    {
        try {
            $transactionDetail = Transaction::findOrFail($transactionId);

            if ($transactionId) {
                $prDetails = PurchaseDetail::where('transaction_id', $transactionId)->first();

                if ($prDetails) {
                    $prDetails->update([
                        'budget_no'   => $request->budget_no,
                        'pr_no'       => $request->pr_no,
                        'po_no'       => $request->po_no,
                        'iar'         => $request->iar
                    ]);

                    $transactionDetail->update([
                        'allocation_id'     => $request->program,
                        'date'              => $request->date,
                        'obr_no'            => $request->obr_no,
                        'obr_amount'        => $request->obr_amount,
                        'obr_month'         => $request->obr_month,
                        'obr_year'          => $request->obr_year,
                        'creditor'          => $request->creditor,
                        'dv_no'             => $request->dv_no,
                        'dv_amount'         => $request->dv_amount,
                        'dv_month'          => $request->dv_month,
                        'dv_year'           => $request->dv_year,
                        'obr_unpaid'        => $request->obr_unpaid,
                        'ada_no'            => $request->ada_no,
                        'activity_title'    => $request->act_title,
                        'saa_title'         => $request->saa_title,
                        'remarks'           => $request->remarks,
                    ]);

                    if (count($request->accounts)) {
                        $uacs = UacsTransaction::where('transaction_id', $transactionId)->delete();

                        foreach($request->accounts as $account) {
                            if ($account['uacs_id'] !== NULL) {
                                UacsTransaction::create([
                                    'transaction_id'    => $transactionId,
                                    'uacs_id'           => $account['uacs_id'],
                                    'amount'            => $account['amount']
                                ]);
                            }
                        }
                    }
                }

                return ResponseHelper::success(message: "Successfully retrieved transaction detail.", data: $transactionDetail, statusCode: 200);
            }

            return ResponseHelper::error("Unable to update transaction detail", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to update transaction. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to update transaction! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Get DV number
     * @param $transactionId
     * @return responseJSON
     */
    public function getDv($transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);

            if ($transaction->dv_no) {
                return ResponseHelper::success(message: "Successfully retrieved transaction detailx.", data: $transaction->dv_no, statusCode: 200);
            } else {
                $latestDvNumber = Transaction::where('dv_no', '!=', null)->orderBy('dv_timestamp', 'ASC')->first();

                if (empty($latestDvNumber)) {
                    $year = date('Y');
                    $month = date('m');
                    $day = date('d');
                    $newDvNumber = "DV-$year-$month-$day-001";

                    return ResponseHelper::success(message: "Successfully retrieved transaction detaila.", data: $newDvNumber, statusCode: 200);
                } else {
                    $explodedDvNumber = explode('-', $latestDvNumber->dv_no);
                    $explodedDvNumber[4] = str_pad($explodedDvNumber[4] + 1, 3, '0', STR_PAD_LEFT);
                    $implodedDvNumber = implode('-', $explodedDvNumber);
                    return ResponseHelper::success(message: "Successfully retrieved transaction detaila.", data: $implodedDvNumber, statusCode: 200);
                }
            }
        } catch (Exception $e) {
            Log::error("Unable to retrieve DV number. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve DV number! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Save a new transaction
     * @param App\Http\Requests\TransactionRequest
     * @return responseJSON
     */
    public function store(TransactionRequest $request)
    {
        try {
            $transactionDetail = Transaction::create([
                'creator'           => Auth::user()->id,
                'is_pr'             => 0,
                'from'              => Auth::user()->id,
                'to'                => Auth::user()->id,
                'received'          => 1,
                'reference_no'      => $request->reference_no,
                'activity_title'    => $request->activity_title
            ]);

            if ($transactionDetail) {
                $name = Auth::user()->firstname . " " . Auth::user()->lastname;
                
                ActivityLog::create([
                    'is_transaction'    => 1,
                    'transaction_id'    => $transactionDetail->id,
                    'from'              => Auth::user()->id,
                    'activity'          => "$name added a new transaction with reference number: $request->reference_no"
                ]);

                return ResponseHelper::success(message: "Successfully save a new transaction.", data: $transactionDetail, statusCode: 200);
            }

            return ResponseHelper::error("Unable to save a new transaction", statusCode: 500);
        } catch  (Exception $e) {
            Log::error("Unable to save a new transaction. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to save a new transaction! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Update other transactions (e.g: TEV and others)
     * @param App\Http\Requests\TransactionUpdateRequest $request
     * @param $transactionId
     * @return responseJSON
     */
    public function updateOtherTransaction(TransactionUpdateRequest $request, $transactionId)
    {
        try {
            $transaction = Transaction::findOrFail($transactionId);

            if ($transaction) {
                $transaction->update([
                    'allocation_id'     => $request->program,
                    'date'              => $request->date,
                    'obr_no'            => $request->obr_no,
                    'obr_amount'        => $request->obr_amount,
                    'obr_month'         => $request->obr_month,
                    'obr_year'          => $request->obr_year,
                    'creditor'          => $request->creditor,
                    'dv_no'             => $request->dv_no,
                    'dv_amount'         => $request->dv_amount,
                    'dv_month'          => $request->dv_month,
                    'dv_year'           => $request->dv_year,
                    'obr_unpaid'        => $request->obr_unpaid,
                    'ada_no'            => $request->ada_no,
                    'activity_title'    => $request->act_title,
                    'saa_title'         => $request->saa_title,
                    'remarks'           => $request->remarks,
                ]);

                if (count($request->accounts)) {
                    $uacs = UacsTransaction::where('transaction_id', $transactionId)->delete();

                    foreach($request->accounts as $account) {
                        if ($account['uacs_id'] !== NULL) {
                            UacsTransaction::create([
                                'transaction_id'    => $transactionId,
                                'uacs_id'           => $account['uacs_id'],
                                'amount'            => $account['amount']
                            ]);
                        }
                    }
                }

                return ResponseHelper::success(message: "Successfully save a new transaction.", data: $transaction, statusCode: 200);
            }

            return ResponseHelper::error("Unable to update transaction", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to update transaction. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to update transaction! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Print transmittal
     * @param Integer $transactionId
     * @return responseJSON
     */
    public function printTransmittal($transactionId)
    {
        try {
            $transaction = Transaction::where('id', $transactionId)->first();

            $data = [
                'transactionDetails'    => $transaction
            ];

            $pdf_output = PDF::loadView('pdf.routingSlip', $data);
            $paperSize = array(0, 0, 421, 298);
            $pdf_output->setPaper($paperSize, 'portrait');

            return $pdf_output->stream('transmittal.pdf');
        } catch (Exception $e) {
            Log::error("Unable to produce a transmittal. : " . $e->getMessage() . " - Line no. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to produce a transmittal! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
