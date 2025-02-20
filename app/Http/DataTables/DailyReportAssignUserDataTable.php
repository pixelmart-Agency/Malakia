<?php

namespace App\Http\DataTables;

use App\Models\DailyReportAssignUser as ObjModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;


class DailyReportAssignUserDataTable extends DataTable
{
    public function __construct(
        protected ObjModel $objModel,
    )
    {
        parent::__construct($objModel);
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('deadline', function ($query) {
                return \Carbon\Carbon::parse($query->deadline)->locale('ar')->translatedFormat('d F Y');
            })->addColumn('actions', function ($query) {
                return '
                         <a href="' . route('daily_report_assign_user.show', ['daily_report_assign_user_id' => $query->id]) . '" class="view">
                              <img class="h-24" src="' . asset('web/image/eye-icon.png') . '" >
                               عرض
                           </a>
                ';
            })->addColumn('title', function ($query) {
                return $query->dailyReport->title;
            })
            ->editColumn('user_id', function ($query) {
                return $query->user->full_name;
            })->editColumn('leader_id', function ($query) {
                return $query->leader->full_name;
            })->editColumn('status', function ($query) {

                if ($query->status == '0') {
                    return '<span class="status-new">لم يتم البدء</span>';
                } elseif ($query->status == '1') {
                    return '<span class="status-new">تم البدء</span>';
                } elseif ($query->status == '2') {
                    return '<span class="status-new">تحت المراجعه</span>';
                }elseif ($query->status == '3') {
                    return '<span class="status-refuse">تحتاج للتعديل</span>';
                }elseif ($query->status == '4') {
                    return '<span class="status-accept">منتهه</span>';
                }else{
                    return '<span class="">غير معروف</span>';
                }
            })


                ->rawColumns(['actions','status']);

    }

    public function query(ObjModel $model): QueryBuilder
    {
        return $model->newQuery();
    }

//    public function ajax(): \Illuminate\Http\JsonResponse
//    {
//        return $this->dataTable(ObjModel::query())
//            ->make(true);
//    }
}
