<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\DataTables\ActivityLogDataTable;
use App\Services\Web\ActivityLogService as ObjService;

class ActivityLogController extends Controller
{
    public function __construct(protected ObjService $objService)
    {
    }
    public function indexDatatable(ActivityLogDataTable $dataTable)
    {
        return $dataTable->render('web.activity_log.index');
    }
    public function index()
    {
        return $this->objService->index();
    }

}
