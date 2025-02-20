<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Services\Web\AreaLocationService as ObjService;
use App\Services\Web\AreaTeamService;
use App\Services\Web\UserService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AreaLocationController extends Controller
{
    public function __construct(

        protected ObjService      $objService,
        protected AreaTeamService $areaTeamService,
        protected UserService     $userService
    ){}

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

}
