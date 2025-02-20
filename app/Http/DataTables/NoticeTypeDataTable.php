<?php

namespace App\Http\DataTables;

use App\Models\NoticeType as ObjModel;
use App\Services\Web\NoticeService;
use App\Services\Web\UserService;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;


class NoticeTypeDataTable extends DataTable
{
    public function __construct(
        protected ObjModel    $objModel,
        protected UserService $userService
    )
    {
        parent::__construct($objModel);
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('name', function ($query) {
                return '<div class="d-flex">
                            <div class="icon-table">
                                <img src="' . asset('web/image/information.png') . '" alt="no-image">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="name-table mb-2">' . $query->name . '</h6>
                            </div>
                        </div>
                        ';
            })
            ->editColumn('priority', function ($query) {
                $priority = $query->priority;
                if ($priority == 'high') {
                    return '
                    <div class="table-info text-green">عالي</div>
                    ';

                } elseif ($priority == 'mid') {
                    return '
                    <div class="table-info text-brown">متوسط</div>
                    ';
                } elseif ($priority == 'low') {
                    return '
                    <div class="table-info text-red">منخفض</div>
                    ';
                } else {
                    return '
                    <div class="table-info text-secondary">إقتراح</div>
                    ';
                }
            })
            ->editColumn('period', function ($query) {
                if ($query->period == '') {

                } elseif ($query->period == '') {

                }
                elseif($query->period == ''){

                }else{

                }
            })
            ->editColumn('level', function ($query) {
        return $query->level;
    })
        ->editColumn('created_at', function ($query) {
            return \Carbon\Carbon::parse($query->created_at)->locale('ar')->translatedFormat('d F Y');
        })
        ->addColumn('actions', function ($query) {
            return '
                        <div class="table-info">
                            <button class="btn-menu dropdown-toggle" type="button"
                                    id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical icon-table"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item">
                                        <img class="h-24" src="' . asset('web/image/eye-icon.png') . '" alt="no-image">
                                        عرض
                                    </a></li>
                                <li><a class="dropdown-item">
                                        <img class="h-24" src="' . asset('web/image/edit-icon.png') . '" alt="no-image">
                                        تعديل
                                    </a></li>
                                <li><a class="dropdown-item" href="#">
                                        <img class="h-24" src="' . asset('web/image/trash.png') . '" alt="no-image">
                                        حذف
                                    </a></li>
                            </ul>
                        </div>
                ';
        })
        ->rawColumns(['name', 'actions', 'status', 'priority']);
    }

    public
    function query(ObjModel $model): QueryBuilder
    {
        return $model->newQuery();
    }
}
