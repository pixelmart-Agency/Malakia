<?php

namespace App\Http\DataTables;

use App\Models\User as ObjModel;
use App\Services\Web\AreaService;
use App\Services\Web\AxisQuestionService;
use App\Services\Web\AreaTeamService;
use App\Services\Web\UserService;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use App\Services\Web\DailyReportService;
use App\Services\Web\DailyReportAssignService;


class UserDataTable extends DataTable
{
    public function __construct(
        protected ObjModel                 $objModel,
        protected DailyReportAssignService $dailyReportAssignService,
        protected DailyReportService       $dailyReportService,
        protected AxisQuestionService      $axisQuestionService,
        protected AreaService              $areaService,
        protected AreaTeamService          $areaTeamService,
        protected UserService              $userService,
        protected Role                     $role
    )
    {
        parent::__construct($objModel);
    }

    public function StatusDatatableCustom($objModel, $status = 1, $column = 'status')
    {
        return '<div class="form-check form-switch d-flex">
                <label class="form-check-label ms-2" for="statusSwitch-' . $objModel->id . '">فعال</label>
                <input class="form-check-input ms-0" type="checkbox"
                       id="statusSwitch-' . $objModel->id . '"
                       data-id="' . $objModel->id . '"
                       name="status"
                       onchange="updateStatus(this)"
                       ' . ($objModel->{$column} == $status ? 'checked' : '') . ' />
            </div>';
    }


    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('id', function ($query) {
                return $query->id;
            })
            ->editColumn('name', function ($query) {


                return '
                        <div class="d-flex">
                            <img class="image-table" src="' . getFile($query->image,'assets/uploads/avatar.png') . '" alt="no-image">
                           <div>
                                <h6 class="name-table d-flex align-items-center">' . $query->full_name . '</h6>
                                <p class="fs-12 fw-400 text-secondary">سعودي</p>
                            </div>
                        </div>
                ';
            })
            ->editColumn('national_id', function ($query) {
                return $query->national_id;
            })
            ->editColumn('area_location', function ($query) {
                $areaNames = $this->objModel
                    ->with('areas.area')
                    ->where('id', $query->id)
                    ->first()
                    ->areas
                    ->pluck('area.name')
                    ->toArray();
                if (count($areaNames) == 0) {
                    return 'لا يوجد مناطق';
                } else {
                    $displayNames = array_slice($areaNames, 0, 3);
                    $more = count($areaNames) > 3 ? '...' : '';
                    return implode(' - ', $displayNames) . $more;
                }
            })
            ->editColumn('role', function ($query) {
                $user = $this->objModel->find($query->id);
                return $user->getroleNames()[0];
            })
            ->editColumn('created_at', function ($query) {

                return \Carbon\Carbon::parse($query->created_at)->locale('ar')->translatedFormat('d F Y');

            })
            ->editColumn('status', function ($obj) {
                return $this->StatusDatatableCustom($obj, '1');
            })
            ->addColumn('actions', function ($query) {
                return '
                            <button class="view border-0 edit" data-bs-toggle="modal"  data-id="' . $query->id . '">
                                <img class="h-24" src="' . asset('web/image/edit-icon.png') . '" alt="no-image">
                                تعديل
                            </button>
                        ';
            })
            ->rawColumns(['name', 'area_location', 'status', 'actions']);
    }

    public function query(ObjModel $model): QueryBuilder
    {
        return $model->newQuery();
    }
}
