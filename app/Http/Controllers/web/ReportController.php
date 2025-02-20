<?php

namespace App\Http\Controllers\web;

use App\Enum\DailyReportRejectReasonEnum;
use App\Http\Controllers\Controller;
use App\Http\DataTables\GeneralReportDataTable;
use App\Http\DataTables\ReportDataTable;
use App\Http\DataTables\ViolationReportDataTable;
use App\Models\GeneralReport;
use App\Models\ViolationReport;
use App\Services\Web\ReportService as ObjService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(protected ObjService $objService, protected ViolationReport $violationReport, protected GeneralReport $generalReport)
    {
    }

    public function index()
    {
        return $this->objService->index();
    }

    public function generalReportIndexDatatable(GeneralReportDataTable $dataTable)
    {
        return $this->objService->indexDatatable($dataTable);
    }

    public function violationReportIndexDatatable(ViolationReportDataTable $dataTable)
    {
        return $this->objService->indexDatatable($dataTable);
    }

    public function generalReportShow($id)
    {
        $refuseReasons = array_map(fn($reason) => strtolower(str_replace('_', ' ', $reason->name)), DailyReportRejectReasonEnum::cases());
        $report = $this->generalReport->where('id', $id)->first();
        return view('web.report.show_general_report', ['report' => $report, 'refuse_reasons' => $refuseReasons]);
    }

    public function violationReportShow($id)
    {
        $refuseReasons = array_map(fn($reason) => strtolower(str_replace('_', ' ', $reason->name)), DailyReportRejectReasonEnum::cases());
        $report = $this->violationReport->where('id', $id)->first();

        return view('web.report.show_violation_report', ['report' => $report, 'refuse_reasons' => $refuseReasons]);
    }

    public function updateReportStatus(Request $request, $id)
    {
            return $this->objService->updateReportStatus($request,$id);
    }



}
