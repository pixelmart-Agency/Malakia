<?php

namespace App\Http\DataTables;

use App\Models\DailyReport as ObjModel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;


class DailyReportDataTable extends DataTable
{
    public function __construct(
        protected ObjModel                 $objModel,
    )
    {
        parent::__construct($objModel);
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('created_at', function ($query) {
                return $query->created_at->locale('ar')->translatedFormat('d F Y');

            }) ->addColumn('actions', function ($query) {
                return '
                            <a href="' . route('daily_report.show', ['daily_report_id' => $query->id]) . '" class="view">
                                <img class="h-24" src="' . asset('web/image/eye-icon.png') . '" >
                                عرض
                            </a>
                ';
            })
            ->rawColumns(['actions']);
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
