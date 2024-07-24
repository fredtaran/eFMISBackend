<?php

namespace App\Http\Controllers\Api;

use Exception;
use Carbon\Carbon;
use App\Models\Uacs;
use App\Models\Allocation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\DB;
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
                $transactions['obr_q1'] = Transaction::all()->whereIn('obr_month', [1, 2, 3])->sum("obr_amount");
                $transactions['obr_q2'] = Transaction::all()->whereIn('obr_month', [4, 5, 6])->sum("obr_amount");
                $transactions['obr_q3'] = Transaction::all()->whereIn('obr_month', [7, 8, 9])->sum("obr_amount");
                $transactions['obr_q4'] = Transaction::all()->whereIn('obr_month', [10, 11, 12])->sum("obr_amount");
                $total_obr = $transactions['obr_q1'] + $transactions['obr_q2'] + $transactions['obr_q3'] + $transactions['obr_q4'];
                $transactions['d_q1'] = Transaction::all()->whereIn('dv_month', [1, 2, 3])->sum("dv_amount");
                $transactions['d_q2'] = Transaction::all()->whereIn('dv_month', [4, 5, 6])->sum("dv_amount");
                $transactions['d_q3'] = Transaction::all()->whereIn('dv_month', [7, 8, 9])->sum("dv_amount");
                $transactions['d_q4'] = Transaction::all()->whereIn('dv_month', [10, 11, 12])->sum("dv_amount");
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
                $transactions['obr_q1'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->whereIn('obr_month', [1, 2, 3])->sum("obr_amount");
                $transactions['obr_q2'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->whereIn('obr_month', [4, 5, 6])->sum("obr_amount");
                $transactions['obr_q3'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->whereIn('obr_month', [7, 8, 9])->sum("obr_amount");
                $transactions['obr_q4'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->whereIn('obr_month', [10, 11, 12])->sum("obr_amount");
                $total_obr = $transactions['obr_q1'] + $transactions['obr_q2'] + $transactions['obr_q3'] + $transactions['obr_q4'];
                $transactions['d_q1'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->whereIn('dv_month', [1, 2, 3])->sum("dv_amount");
                $transactions['d_q2'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->whereIn('dv_month', [4, 5, 6])->sum("dv_amount");
                $transactions['d_q3'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->whereIn('dv_month', [7, 8, 9])->sum("dv_amount");
                $transactions['d_q4'] = Transaction::all()->where('allocation_id', $request->query('allocation_id'))->whereIn('dv_month', [10, 11, 12])->sum("dv_amount");
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

    /**
     * Function: Retrieve report
     * @param Illuminate\Http\Request $request
     * @return responseJSON
     */
    public function getReportByAccountTitle(Request $request)
    {
        try {
            $query = "SELECT 
                        title,
                        SUM(IF(obr_month = 1, amount, 0)) AS Jan,
                        SUM(IF(obr_month = 2, amount, 0)) AS Feb,
                        SUM(IF(obr_month = 3, amount, 0)) AS Mar,
                        SUM(IF(obr_month = 4, amount, 0)) AS Apr,
                        SUM(IF(obr_month = 5, amount, 0)) AS May,
                        SUM(IF(obr_month = 6, amount, 0)) AS Jun,
                        SUM(IF(obr_month = 7, amount, 0)) AS Jul,
                        SUM(IF(obr_month = 8, amount, 0)) AS Aug,
                        SUM(IF(obr_month = 9, amount, 0)) AS Sep,
                        SUM(IF(obr_month = 10, amount, 0)) AS `Oct`,
                        SUM(IF(obr_month = 11, amount, 0)) AS Nov,
                        SUM(IF(obr_month = 12, amount, 0)) AS `Dec`
                        FROM 
                        (
                            SELECT 
                                uacs.title, 
                                transactions.obr_month, 
                                uacs_transactions.amount
                            FROM  uacs
                            JOIN uacs_transactions ON uacs.id = uacs_transactions.uacs_id
                            JOIN transactions ON transactions.id = uacs_transactions.transaction_id
                            WHERE transactions.allocation_id = ?
                            AND YEAR(transactions.obr_timestamp) = ?
                        ) AS subquery
                        GROUP BY 
                            title
                        ORDER BY 
                            title";

            $result = DB::select($query, [$request->query('program'), $request->query('year')]);
        
            return ResponseHelper::success(message: "Summary report retrieved successfully!", data: $result, statusCode: 200);
        } catch (Exception $e) {
            Log::error("Unable to retrieved report: " . $e->getMessage() . " - Line No. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve report! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }
}
