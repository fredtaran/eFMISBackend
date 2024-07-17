<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Allocation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    
    /**
     * Function: Get summary report
     * @param Illuminate\Http\Request $request
     * @return responseJSON
     */
    public function summaryReport(Request $request)
    {
        try {
            if ($request->query('allocation_id') == 0) {
                $transactions['annual_allocation']  = Allocation::query()->where('year', date('Y'))->sum("amount");
                $transactions['obr_q1'] = Transaction::all()->where('year', date('Y'))->whereIn('obr_month', [1, 2, 3])->sum("obr_amount");
                $transactions['obr_q2'] = Transaction::all()->where('year', date('Y'))->whereIn('obr_month', [4, 5, 6])->sum("obr_amount");
                $transactions['obr_q3'] = Transaction::all()->where('year', date('Y'))->whereIn('obr_month', [7, 8, 9])->sum("obr_amount");
                $transactions['obr_q4'] = Transaction::all()->where('year', date('Y'))->whereIn('obr_month', [10, 11, 12])->sum("obr_amount");
                $total_obr = $transactions['obr_q1'] + $transactions['obr_q2'] + $transactions['obr_q3'] + $transactions['obr_q4'];
                $transactions['d_q1'] = Transaction::all()->where('year', date('Y'))->whereIn('dv_month', [1, 2, 3])->sum("dv_amount");
                $transactions['d_q2'] = Transaction::all()->where('year', date('Y'))->whereIn('dv_month', [4, 5, 6])->sum("dv_amount");
                $transactions['d_q3'] = Transaction::all()->where('year', date('Y'))->whereIn('dv_month', [7, 8, 9])->sum("dv_amount");
                $transactions['d_q4'] = Transaction::all()->where('year', date('Y'))->whereIn('dv_month', [10, 11, 12])->sum("dv_amount");
                $total_disbursement = $transactions['d_q1'] + $transactions['d_q2'] + $transactions['d_q3'] + $transactions['d_q4'];
                $transactions['annual_balance'] = $transactions['annual_allocation'] - $total_disbursement;
                if ($transactions['annual_allocation'] !== 0) {
                    $transactions['annual_obr']  = round(($total_obr / $transactions['annual_allocation']) * 100, 2);
                    $transactions['annual_dsr']  = round(($total_disbursement / $transactions['annual_allocation']) * 100, 2);
                }
                $transactions['program_name'] = "";

                return ResponseHelper::success(message: "Summary report retrieved successfully!", data: $transactions, statusCode: 200);
            } else {
                $transactions['annual_allocation']  = Allocation::all()->where('id', $request->query('allocation_id'))->sum("amount");
                $transactions['obr_q1'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->where('year', date('Y'))->whereIn('obr_month', [1, 2, 3])->sum("obr_amount");
                $transactions['obr_q2'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->where('year', date('Y'))->whereIn('obr_month', [4, 5, 6])->sum("obr_amount");
                $transactions['obr_q3'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->where('year', date('Y'))->whereIn('obr_month', [7, 8, 9])->sum("obr_amount");
                $transactions['obr_q4'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->where('year', date('Y'))->whereIn('obr_month', [10, 11, 12])->sum("obr_amount");
                $total_obr = $transactions['obr_q1'] + $transactions['obr_q2'] + $transactions['obr_q3'] + $transactions['obr_q4'];
                $transactions['d_q1'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->where('year', date('Y'))->whereIn('dv_month', [1, 2, 3])->sum("dv_amount");
                $transactions['d_q2'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->where('year', date('Y'))->whereIn('dv_month', [4, 5, 6])->sum("dv_amount");
                $transactions['d_q3'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->where('year', date('Y'))->whereIn('dv_month', [7, 8, 9])->sum("dv_amount");
                $transactions['d_q4'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->where('year', date('Y'))->whereIn('dv_month', [10, 11, 12])->sum("dv_amount");
                $total_disbursement = $transactions['d_q1'] + $transactions['d_q2'] + $transactions['d_q3'] + $transactions['d_q4'];
                $transactions['annual_balance'] = $transactions['annual_allocation'] - $total_disbursement;
                $transactions['annual_obr']  = round(($total_obr / $transactions['annual_allocation']) * 100, 2);
                $transactions['annual_dsr']  = round(($total_disbursement / $transactions['annual_allocation']) * 100, 2);
                $transactions['program_name'] = Allocation::select('program')->where('id', $request->query('allocation_id'))->first();


                return ResponseHelper::success(message: "Summary report retrieved successfully!", data: $transactions, statusCode: 200);
            }

            return ResponseHelper::error(message: "Unable to retrieved summary report! Try again. ", statusCode: 500);
        } catch (Exception $e) {
            Log::error("Unable to retrieved summary report: " . $e->getMessage() . " - Line No. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve summary report! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
