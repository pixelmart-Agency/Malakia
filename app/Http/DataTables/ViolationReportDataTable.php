<?php

namespace App\Http\DataTables;

use App\Models\ViolationReport as ObjModel;
use App\Services\Web\UserService;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;



class ViolationReportDataTable extends DataTable
{
    public function __construct(
        protected ObjModel $objModel,
        protected UserService $userService
    )
    {
        parent::__construct($objModel);
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('title', function ($query) {
                return '<div class="d-flex">
                            <div class="icon-table">
                                <img src="'.asset('web/image/file-icon.png').'" alt="no-image">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="name-table mb-2">'.$query->title.'</h6>
                                <p class="file-size">3.4 MB</p>
                            </div>
                        </div>';
            })
            ->editColumn('user', function ($query) {
                $user=$this->userService->model->where('id',$query->user_id)->first();
                return '<div class="d-flex">
                            <img class="image-table" src="'.getFile($user->image,'assets/uploads/avatar.png').'" alt="no-image">
                            <h6 class="name-table d-flex align-items-center">'.$user->full_name .'</h6>
                        </div>';
            })
            ->editColumn('role', function ($query) {
                $user = $this->userService->model->find($query->id);
//                dd($user->getroleNames()[0]);// Get the user by model_id
                return $user->getroleNames()[0];
            })
            ->editColumn('status', function ($query) {
                if ($query->status=='1'){
                     return ' <span class="status-accept">
                            معتمد
                         </span>';
                }
                elseif ($query->status=='2'){
                     return ' <span class="status-refuse">
                                        مرفوضة
                              </span>';
                 }else{
                     return   ' <span class="status-new">
                            معلق
                         </span>';
                 }

            })
            ->editColumn('created_at', function ($query) {
                return \Carbon\Carbon::parse($query->created_at)->locale('ar')->translatedFormat('d F Y');
            })
            ->addColumn('actions', function ($query) {
                return '
                            <a href="' . route('violation_report.show', ['violation_report_id' => $query->id]) . '" class="view">
                                <img class="h-24" src="' . asset('web/image/eye-icon.png') . '" >
                                عرض
                            </a>
                ';
            })
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

            ->rawColumns(['title','user','actions','role','status']);
    }

    public
    function query(ObjModel $model): QueryBuilder
    {
        return $model->newQuery();
    }
}
