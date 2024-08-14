<?php

namespace App\Http\Controllers\Api;

use PDF;
use Exception;
use Carbon\Carbon;
use App\Models\Uacs;
use App\Models\Allocation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use App\Helper\ResponseHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

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
     * Function: Retrieve data for report by account title
     * @param Illuminate\Http\Request $request
     * @return responseJSON
     */
    public function getReportByAccountTitle(Request $request)
    {
        try {
            if ($request->query('report') == 1) {
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
                            AND transactions.obr_year = ?
                        ) AS subquery
                        GROUP BY 
                            title
                        ORDER BY 
                            title";
            } else {
                $query = "SELECT 
                            title,
                            SUM(IF(dv_month = 1, dv_amount, 0)) AS Jan,
                            SUM(IF(dv_month = 2, dv_amount, 0)) AS Feb,
                            SUM(IF(dv_month = 3, dv_amount, 0)) AS Mar,
                            SUM(IF(dv_month = 4, dv_amount, 0)) AS Apr,
                            SUM(IF(dv_month = 5, dv_amount, 0)) AS May,
                            SUM(IF(dv_month = 6, dv_amount, 0)) AS Jun,
                            SUM(IF(dv_month = 7, dv_amount, 0)) AS Jul,
                            SUM(IF(dv_month = 8, dv_amount, 0)) AS Aug,
                            SUM(IF(dv_month = 9, dv_amount, 0)) AS Sep,
                            SUM(IF(dv_month = 10, dv_amount, 0)) AS `Oct`,
                            SUM(IF(dv_month = 11, dv_amount, 0)) AS Nov,
                            SUM(IF(dv_month = 12, dv_amount, 0)) AS `Dec`
                        FROM 
                        (
                            SELECT 
                                uacs.title, 
                                transactions.dv_month, 
                                transactions.dv_amount
                            FROM  uacs
                            JOIN uacs_transactions ON uacs.id = uacs_transactions.uacs_id
                            JOIN transactions ON transactions.id = uacs_transactions.transaction_id
                            WHERE transactions.allocation_id = ?
                            AND transactions.dv_year = ?
                        ) AS subquery
                        GROUP BY 
                            title
                        ORDER BY 
                            title";
            }

            $result = DB::select($query, [$request->query('program'), $request->query('year')]);
        
            return ResponseHelper::success(message: "Summary report retrieved successfully!", data: $result, statusCode: 200);
        } catch (Exception $e) {
            Log::error("Unable to retrieved report: " . $e->getMessage() . " - Line No. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve report! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve data for report by program
     * @param Illuminate\Http\Request $request
     * @return responseJSON
     */
    public function getReportByProgram(Request $request)
    {
        try {
            if ($request->query('report') == 1) {
                $result = DB::table('allocations')
                            ->join('transactions', 'transactions.allocation_id', '=', 'allocations.id')
                            ->where('transactions.obr_year', $request->query('year'))
                            ->where('allocations.fs_id', $request->query('fundSource'))
                            ->select(
                                'allocations.program',
                                'allocations.amount as annual_allocation',
                                DB::raw('SUM(IF(transactions.obr_month = 1, transactions.obr_amount, 0)) as Jan'),
                                DB::raw('SUM(IF(transactions.obr_month = 2, transactions.obr_amount, 0)) as Feb'),
                                DB::raw('SUM(IF(transactions.obr_month = 3, transactions.obr_amount, 0)) as Mar'),
                                DB::raw('SUM(IF(transactions.obr_month = 4, transactions.obr_amount, 0)) as Apr'),
                                DB::raw('SUM(IF(transactions.obr_month = 5, transactions.obr_amount, 0)) as May'),
                                DB::raw('SUM(IF(transactions.obr_month = 6, transactions.obr_amount, 0)) as Jun'),
                                DB::raw('SUM(IF(transactions.obr_month = 7, transactions.obr_amount, 0)) as Jul'),
                                DB::raw('SUM(IF(transactions.obr_month = 8, transactions.obr_amount, 0)) as Aug'),
                                DB::raw('SUM(IF(transactions.obr_month = 9, transactions.obr_amount, 0)) as Sep'),
                                DB::raw('SUM(IF(transactions.obr_month = 10, transactions.obr_amount, 0)) as `Oct`'),
                                DB::raw('SUM(IF(transactions.obr_month = 11, transactions.obr_amount, 0)) as Nov'),
                                DB::raw('SUM(IF(transactions.obr_month = 12, transactions.obr_amount, 0)) as `Dec`')
                            )
                            ->groupBy(['allocations.program', 'allocations.amount'])
                            ->orderBy('allocations.program')
                            ->get();
            } else {
                $result = DB::table('allocations')
                            ->join('transactions', 'transactions.allocation_id', '=', 'allocations.id')
                            ->where('transactions.obr_year', $request->query('year'))
                            ->where('allocations.fs_id', $request->query('fundSource'))
                            ->select(
                                'allocations.program',
                                'allocations.amount as annual_allocation',
                                DB::raw('SUM(IF(transactions.dv_month = 1, transactions.dv_amount, 0)) as Jan'),
                                DB::raw('SUM(IF(transactions.dv_month = 2, transactions.dv_amount, 0)) as Feb'),
                                DB::raw('SUM(IF(transactions.dv_month = 3, transactions.dv_amount, 0)) as Mar'),
                                DB::raw('SUM(IF(transactions.dv_month = 4, transactions.dv_amount, 0)) as Apr'),
                                DB::raw('SUM(IF(transactions.dv_month = 5, transactions.dv_amount, 0)) as May'),
                                DB::raw('SUM(IF(transactions.dv_month = 6, transactions.dv_amount, 0)) as Jun'),
                                DB::raw('SUM(IF(transactions.dv_month = 7, transactions.dv_amount, 0)) as Jul'),
                                DB::raw('SUM(IF(transactions.dv_month = 8, transactions.dv_amount, 0)) as Aug'),
                                DB::raw('SUM(IF(transactions.dv_month = 9, transactions.dv_amount, 0)) as Sep'),
                                DB::raw('SUM(IF(transactions.dv_month = 10, transactions.dv_amount, 0)) as `Oct`'),
                                DB::raw('SUM(IF(transactions.dv_month = 11, transactions.dv_amount, 0)) as Nov'),
                                DB::raw('SUM(IF(transactions.dv_month = 12, transactions.dv_amount, 0)) as `Dec`')
                            )
                            ->groupBy(['allocations.program', 'allocations.amount'])
                            ->orderBy('allocations.program')
                            ->get();
            }

            return ResponseHelper::success(message: "Summary report retrieved successfully!", data: $result, statusCode: 200);
        } catch (Exception $e) {
            Log::error("Unable to retrieved report: " . $e->getMessage() . " - Line No. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve report! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Function: Retrieve data for report by fund source
     * @param Illuminate\Http\Request $request
     * @return responseJSON
     */
    public function getReportByFundSource(Request $request)
    {
        try {
            if ($request->query('report') == 1) {
                $query = "SELECT
                            `code`,
                            SUM(IF(obr_month = 1, total_obr_amount, 0)) AS Jan,
                            SUM(IF(obr_month = 2, total_obr_amount, 0)) AS Feb,
                            SUM(IF(obr_month = 3, total_obr_amount, 0)) AS Mar,
                            SUM(IF(obr_month = 4, total_obr_amount, 0)) AS Apr,
                            SUM(IF(obr_month = 5, total_obr_amount, 0)) AS May,
                            SUM(IF(obr_month = 6, total_obr_amount, 0)) AS Jun,
                            SUM(IF(obr_month = 7, total_obr_amount, 0)) AS Jul,
                            SUM(IF(obr_month = 8, total_obr_amount, 0)) AS Aug,
                            SUM(IF(obr_month = 9, total_obr_amount, 0)) AS Sep,
                            SUM(IF(obr_month = 10, total_obr_amount, 0)) AS `Oct`,
                            SUM(IF(obr_month = 11, total_obr_amount, 0)) AS Nov,
                            SUM(IF(obr_month = 12, total_obr_amount, 0)) AS `Dec`
                        FROM (
                            SELECT 
                                fund_sources.`code`,
                                transactions.obr_month,
                                SUM(transactions.obr_amount) AS total_obr_amount
                            FROM transactions
                            JOIN allocations ON allocations.id = transactions.allocation_id
                            JOIN fund_sources ON fund_sources.id = allocations.fs_id
                            WHERE transactions.obr_year = ?
                            AND allocations.line_id = ?
                            GROUP BY transactions.obr_month, fund_sources.code
                        ) AS subquery
                        GROUP BY
                            `code`
                        ORDER BY
                            `code`";
            } else {
                $query = "SELECT
                            `code`,
                            SUM(IF(dv_month = 1, total_dv_amount, 0)) AS Jan,
                            SUM(IF(dv_month = 2, total_dv_amount, 0)) AS Feb,
                            SUM(IF(dv_month = 3, total_dv_amount, 0)) AS Mar,
                            SUM(IF(dv_month = 4, total_dv_amount, 0)) AS Apr,
                            SUM(IF(dv_month = 5, total_dv_amount, 0)) AS May,
                            SUM(IF(dv_month = 6, total_dv_amount, 0)) AS Jun,
                            SUM(IF(dv_month = 7, total_dv_amount, 0)) AS Jul,
                            SUM(IF(dv_month = 8, total_dv_amount, 0)) AS Aug,
                            SUM(IF(dv_month = 9, total_dv_amount, 0)) AS Sep,
                            SUM(IF(dv_month = 10, total_dv_amount, 0)) AS `Oct`,
                            SUM(IF(dv_month = 11, total_dv_amount, 0)) AS Nov,
                            SUM(IF(dv_month = 12, total_dv_amount, 0)) AS `Dec`
                        FROM (
                            SELECT 
                                fund_sources.`code`,
                                transactions.dv_month,
                                SUM(transactions.dv_amount) AS total_dv_amount
                            FROM transactions
                            JOIN allocations ON allocations.id = transactions.allocation_id
                            JOIN fund_sources ON fund_sources.id = allocations.fs_id
                            WHERE transactions.dv_year = ?
                            AND allocations.line_id = ?
                            GROUP BY transactions.dv_month, fund_sources.code
                        ) AS subquery
                        GROUP BY
                            `code`
                        ORDER BY
                            `code`";
            }

            $result = DB::select($query, [$request->query('year'), $request->query('lineItem')]);

            return ResponseHelper::success(message: "Summary report retrieved successfully!", data: $result, statusCode: 200);
        } catch (Exception $e) {
            Log::error("Unable to retrieved report: " . $e->getMessage() . " - Line No. " . $e->getLine());
            return ResponseHelper::error(message: "Unable to retrieve report! Try again. " . $e->getMessage(), statusCode: 500);
        }
    }

    public function generatePdf(Request $request)
    {
        $data = [
            'months' => $request->input('months'),
            'reportDataToDisplay' => explode(",", $request->input('reportDataToDisplay'))
        ];

        $pdf = PDF::loadView('pdf.sampleReport', $data);
        $pdf->setPaper('FOLIO', 'landscape');
        // return view('pdf.sampleReport', $data);
        return $pdf->stream('report.pdf');
        // return $pdf->download('report.pdf');
    }

    public function downloadExcel(Request $request)
    {
        return Excel::download(new ReportExport($request->input('months'), explode(",", $request->input('reportDataToDisplay'))), 'export.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
