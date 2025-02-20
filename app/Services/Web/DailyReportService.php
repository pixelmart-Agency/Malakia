<?php

namespace App\Services\Web;

use App\Models\Axis;
use App\Models\DailyReport as ObjdModel;

use App\Models\DailyReportAssignUser;
use App\Services\BaseService;
use Illuminate\Support\Facades\Validator;

class DailyReportService extends BaseService
{
    protected string $folder = 'web.daily_report';

//    protected string $route = 'web.daily_report';

    public function __construct(ObjdModel $objModel, protected Axis $axis ,protected DailyReportAssignUser $dailyReportAssignUser)
    {
        parent::__construct($objModel);
    }


    public function indexDatatable($dataTable)
    {
        return $dataTable->render('web.daily_report.index');
    }

    public function index()
    {
        return view($this->folder . '.index', [
            'axes' => $this->axis->get(),

        ]);
    }

    public function show($daily_report_id)
    {
        $obj = $this->model->findOrFail($daily_report_id);
        return view($this->folder . '.show', [
            'obj' => $obj,
        ]);

    }

    public function showDailyReportAssignUser($daily_report_assign_user_id)
    {
        $obj = $this->dailyReportAssignUser->findOrFail($daily_report_assign_user_id);
        return view($this->folder.'.showAssignUser',[
            'obj'=>$obj,
        ]);

    }


    public function store($request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'axis_id' => 'required',
            'deadline' => 'required|date|after_or_equal:today',
            'monitor_type' => 'required',
            'side_type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $data = $request->all();

        $obj = $this->model->create(($data));
        if ($obj) {


            return redirect()->route('daily_report.index')->with('success', 'تم انشاء التقرير بنجاح');
        }

        return redirect()->route('daily_report.index')->with('error', 'لم يتم انشاء التقرير ');


    }

}
