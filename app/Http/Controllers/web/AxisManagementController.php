<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\DataTables\AxisManagementDataTable;
use App\Services\Web\AxesManagementService as ObjService;
use Illuminate\Http\Request;

class AxisManagementController extends Controller
{
    public function __construct(protected ObjService $objService)
    {
    }

    public function indexDatatable(AxisManagementDataTable $dataTable)
    {
        return $dataTable->render('web.axes_management.index');
    }


    public function index()
    {
        return $this->objService->index();
    }
    public function create()
    {
        return $this->objService->create();
    }
    public function store(Request $request)
    {
        return $this->objService->store($request);

    }

}
