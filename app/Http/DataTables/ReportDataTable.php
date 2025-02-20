<?php

namespace App\Http\DataTables;

use App\Models\Axis as ObjModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;



class ReportDataTable extends DataTable
{
    public function __construct(
        protected ObjModel                 $objModel,

    )
    {
        parent::__construct($objModel);
    }

//    public function dataTable(QueryBuilder $query): EloquentDataTable
//    {
//        return (new EloquentDataTable($query))
//            ->editColumn('name', function ($query) {
//                return $query->name;
//            })
//            ->editColumn('number_of_questions', function ($query) {
//                return $query->axisQuestions->count();
//            })
//            ->editColumn('area_count', function ($query) {
//                return $this->areaService->model->where('axis_id', $query->id)->count() . ' موقع';
//            })
//
//            ->addColumn('employees', content: function ($query) {
//                $div = '';
//                $areaTeams = $this->areaService->model->with('memberTeam')->where('axis_id', $query->id)->get();
//                $teamMembers = $areaTeams->pluck('memberTeam')->flatten();
//                $div = '<div class="table-info position-relative">';
//                foreach ($teamMembers as $index => $teamMember) {
//                    $usersImage = $this->userService->model->where('id', $teamMember->first()->user_id)->first();
//                    if ($usersImage) {
//                        $div .= '<img class="team-image" style="position: absolute;right: ' . ((20 * $index) + 1) . 'px;" src="' . getFile($usersImage->image,'assets/uploads/avatar.png') . '" alt="no-image">';
//                    }
//                    if ($index >= 3) {
//                        break;
//                    }
//                }
//                if ($teamMembers->count() > 4) {
//                    $div .= '
//                                    <span class="number-team" style="position: absolute;right: 81px;">+' . ($teamMembers->count() - 4) . '</span>
//                               ';
//                }
//                $div .= ' </div>';
//
//                return $div;
//            })
//            ->addColumn('supervisors', content: function ($query) {
//                $div = '';
//
//                $areaTeams = $this->areaService->model->with('leaderTeam')->where('axis_id', $query->id)->get();
//                $teamMembers = $areaTeams->pluck('leaderTeam')->flatten();
//                $div = '<div class="table-info position-relative">';
//                foreach ($teamMembers as $index => $teamMember) {
//                    $usersImage = $this->userService->model->where('id', $teamMember->first()->user_id)->first();
////                    dd($usersImage);
//                    if ($usersImage) {
//                        $div .= '<img class="team-image" style="position: absolute;right: ' . ((20 * $index) + 1) . 'px;" src="' . getFile($usersImage->image,'assets/uploads/avatar.png') . '" alt="no-image">';
//                    }
//                    if ($index >= 3) {
//                        break;
//                    }
//                }
//                if ($teamMembers->count() > 4) {
//
//                    $div .= '
//                                    <span class="number-team" style="position: absolute;right: 81px;">+' . ($teamMembers->count() - 4) . '</span>
//                               ';
//                }
//                $div .= ' </div>';
//
//                return $div;
//            })
//            ->addColumn('actions', function ($query) {
//                return '
//                            <a href="' . route('area', ['axis_id' => $query->id]) . '" class="view">
//                                <img class="h-24" src="' . asset('web/image/eye-icon.png') . '" alt="no-image">
//                                عرض
//                            </a>
//                ';
//
//            })
//
//            ->rawColumns(['employees', 'supervisors', 'actions']);
//    }

    public
    function query(ObjModel $model): QueryBuilder
    {
        return $model->newQuery();
    }
}
