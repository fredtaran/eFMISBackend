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
            // Initialize an empty array to hold the final result
            $allMonthsReport = [];

            // Get all months for the current year
            $months = $this->getAllMonthsForYear($request->query('year'), $request->query('quarter'));

            switch ($request->query('quarter')) {
                case 1:
                    $qtr = '1,2,3';
                    break;
                case 2:
                    $qtr = '1,2,3,4,5,6';
                    break;
                case 3:
                    $qtr = '1,2,3,4,5,6,7,8,9';
                    break;
                case 4:
                    $qtr = '1,2,3,4,5,6,7,8,9,10,11,12';
                    break;
                default:
                    break;
            }

            $transactions = DB::table('transactions')
                                ->join('uacs_transactions', 'transactions.id', '=', 'uacs_transactions.transaction_id')
                                ->join('uacs', 'uacs_transactions.uacs_id', '=', 'uacs.id')
                                ->select(
                                    DB::raw("LPAD(obr_month, 2, '0') AS month"),
                                    'uacs.title',
                                    DB::raw("SUM(transactions.obr_amount) AS total_obr_amount")
                                )
                                ->whereIn('obr_month', explode(',', $qtr))
                                ->groupBy('obr_year', 'obr_month', 'uacs.title')
                                ->orderBy('obr_year', 'ASC')
                                ->orderBy('obr_month', 'ASC')
                                ->get();

            // Merge the fetched transactions with the list of months
            foreach ($months as $month) {

                $reportEntry = [
                    'month' => $month,
                    'total_obr_amount' => $transactions->firstWhere('month', $month)->total_obr_amount ?? 0,
                ];
                $allMonthsReport[$month] = $reportEntry;
            }

            return ResponseHelper::success(message: "Summary report retrieved successfully!", data: $transactions->groupBy('title'), statusCode: 200);
        } catch (Exception $e) {
            Log::error("Unable to retrieved report: " . $e->getMessage() . " - Line No. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve report! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Get months
     * @param Integer $year
     * @return Array $months
     */
    public function getAllMonthsForYear($year, $qtr)
    {
        $months = [];
        for ($i = 1; $i <= $qtr * 3; $i++) {
            $months[] = Carbon::create($year, $i)->startOfMonth()->format('m');
        }
        return $months;
    }
}
