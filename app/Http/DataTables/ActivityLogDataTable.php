<?php

namespace App\Http\DataTables;

use App\Models\Admin;
use App\Models\User;
use Spatie\Activitylog\Models\Activity as ObjModel;
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


class ActivityLogDataTable extends DataTable
{
    public function __construct(
        protected ObjModel                 $objModel,
        protected Admin $user
    )
    {
        parent::__construct($objModel);
    }



    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('description', function ($obj) {
//        dd($obj->all());
                switch ($obj->description) {
                    case 'created':
                        return 'إنشاء';
                    case 'updated':
                        return 'تعديل';
                    case 'deleted':
                        return 'حذف';
                    default:
                        return $obj->description;
                }
            })
//            ->editColumn('subject_type', function ($obj) {
//                return class_basename($obj->subject_type);
////                    return $obj->subject_type;
//            })
//            ->editColumn('subject_id', function ($obj) {
//                return $obj->subject_id;
//            })
//                ->editColumn('causer_type', function ($obj) {
//                    return class_basename($obj->causer_type);
////                    return Str::match('*',$obj->causer_type);
////                    return $obj->causer_type;
//                })
            ->editColumn('causer_id', function ($obj) {
//                dd($obj->causer_id);
//                    return $this->adminObj->first()->name ;
//                    return class_basename($obj->subject_type);
//                dd($this->user->where('id', $obj->causer_id)->first()->full_name);
                return $this->user->where('id', $obj->causer_id)->first()->full_name??"";
            })
            ->editColumn('created_at', function ($obj) {
                return \Carbon\Carbon::parse($obj->created_at)->locale('ar')->translatedFormat('d F Y');

            })
//            ->addColumn('actions', function ($obj) {
//                $buttons = '
//                        <button class="btn btn-pill btn-danger-light" data-bs-toggle="modal"
//                            data-bs-target="#delete_modal" data-id="' . $obj->id . '" data-title="' . $obj->name . '">
//                            <i class="fas fa-trash"></i>
//                        </button>
//                    ';
//                return $buttons;
//            })
//            ->rawColumns([ 'actions'])
            ;
    }

    public function query(ObjModel $model): QueryBuilder
    {
        return $model->newQuery();
    }
}
