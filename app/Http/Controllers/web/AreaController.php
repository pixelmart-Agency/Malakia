<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Services\Web\AreaService as ObjService;
use App\Services\Web\AreaTeamService;
use App\Services\Web\UserService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AreaController extends Controller
{
    public function __construct(

        protected ObjService      $objService,
        protected AreaTeamService $areaTeamService,
        protected UserService     $userService
    ) {}

    public function indexDatatable(Request $request)
    {
        $axisId = $request->query('axis_id'); // Get the passed axis_id
        $query = $this->objService->model->newQuery(); // Start Query
        if ($axisId) {
            $query = $query->where('axis_id', $axisId);
        }
        return DataTables::of($query)
            ->addColumn('area_locations', function ($query) {
                return $query ? $query->name . ' موقع' : 'No Data';
            })
            ->addColumn('area_team', content: function ($query) {
                $div = '';

                $areaTeams = $this->areaTeamService->model->where('area_id', $query->id)->where('type', '0')->get();

                $div = '<div class="table-info position-relative">';
                foreach ($areaTeams as $index => $areaTeam) {
                    $usersImage = $this->userService->model->where('id', $areaTeam->first()->user_id)->first();
                    if ($usersImage) {
                        $div .= '<img class="team-image" style="position: absolute;right: ' . ((20 * $index) + 1) . 'px;" src="' . getFile($usersImage->image, 'assets/uploads/avatar.png') . '" alt="no-image">';
                    }
                    if ($index >= 3) {
                        break;
                    }
                }
                if ($areaTeams->count() > 4) {
                    $div .= '
                                    <span class="number-team" style="position: absolute;right: 81px;">+' . ($areaTeams->count() - 4) . '</span>
                               ';
                }
                $div .= ' </div>';

                return $div;
            })
            ->addColumn('supervisors', content: function ($query) {
                $div = '';
                $areaSupervisor = $this->areaTeamService->model->where('area_id', $query->id)->where('type', 1)->first();
                $supervisorId = $areaSupervisor ? $areaSupervisor->user_id : null;

                if ($supervisorId != null) {
                    $supervisorImage = $this->userService->model->where('id', $supervisorId)->first();
                    $div = '  <div class="d-flex">
                                  <img class="image-table" src="' .  getFile($supervisorImage->image, 'assets/uploads/avatar.png') . '" alt="no-image">
                                  <h6 class="name-table d-flex align-items-center">' . $supervisorImage->full_name . '</h6>
                              </div>';
                } else {
                    $div .= 'لا يوجد مشرفين لهده المنطقه';
                }

                return $div;
            })
            ->addColumn('actions', function ($query) {
                return '
                        <a href="' . route('areaLocation', ['id' => $query->id]) . '" class="view">
                            <img class="h-24" src="' . asset('web/image/eye-icon.png') . '" alt="no-image">
                            عرض
                        </a>
                    ';
            })
            ->rawColumns(['area_locations', 'area_team', 'supervisors', 'actions'])
            ->orderColumn('area_locations', function ($query, $order) {
                return; // Prevent ordering
            })
            ->make(true);
    }

    public function index(Request $request)
    {
        return $this->objService->index($request);
    }

    public function store(Request $request)
    {
        return $this->objService->store($request);
    }
}
