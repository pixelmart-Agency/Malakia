<?php

namespace App\Services\Web;

use App\Models\Area;
use App\Models\AreaLocation as ObjdModel;

use App\Services\BaseService;

class AreaLocationService extends BaseService
{
//    protected string $folder = 'admin/admin';
//    protected string $route = 'adminHome';

    public function __construct(
        ObjdModel                 $objModel,
        protected AreaTeamService $areaTeamService,
        protected AreaService     $areaService,
        protected UserService     $userService,
    )
    {
        parent::__construct($objModel);
    }

    public function index($request)
    {
        $areaId = $request->id;
        $areaTeam = $this->areaTeamService->model->where('area_id', $areaId)->get();
        $teamUsers=[];
        $supervisorUser=[];
        foreach ($areaTeam as $team) {
            $teamUsers[] = ($team->type == 0) ? $this->userService->model->where('id', $team->user_id)->first():null;
            $supervisorUser[] = ($team->type == 1) ? $this->userService->model->where('id', $team->user_id)->first():null;
        }
        $area = $this->areaService->model->where('id', $areaId)->first();
        return view('web.area_location.index', compact('areaTeam', 'area', 'teamUsers', 'supervisorUser'));
    }
}
