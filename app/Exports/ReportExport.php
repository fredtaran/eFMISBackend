<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportExport implements FromView, ShouldAutoSize
{

    use Exportable;

    private $months;
    private $reportDataToDisplay;

    public function __construct($months, $reportDataToDisplay)
    {
        $this->months = $months;
        $this->reportDataToDisplay = $reportDataToDisplay;
    }

    /**
     * 
     */
    public function view(): View
    {
        return view('pdf.sampleReportExcel', [
            'months'                => $this->months,
            'reportDataToDisplay'   => $this->reportDataToDisplay
        ]);
    }
}
