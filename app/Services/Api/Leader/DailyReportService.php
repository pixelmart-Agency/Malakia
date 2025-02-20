<?php

namespace App\Services\Api\Leader;

use App\Enum\DailyReportAssignUserStatusEnum;
use App\Enum\DailyReportRejectReasonEnum;
use App\Enum\LeaderDailyReportAssignUserStatusEnum;
use App\Http\Resources\Leaders\LeaderDailyReportAssignDetailsResource;
use App\Http\Resources\Leaders\LeaderDailyReportAssignResource;
use App\Http\Resources\Leaders\LeaderDailyReportDetailsResource;
use App\Http\Resources\Leaders\LeaderDailyReportQuestionAnswerResource;
use App\Http\Resources\Leaders\LeaderReportResource;
use App\Models\DailyAssignUserAnswer;
use App\Models\DailyReport;
use App\Models\DailyReportAssignUser;
use App\Services\BaseService;

/**
 * Summary of LeaderService
 */
class DailyReportService extends BaseService
{
    /**
     * Summary of __construct
     * @param \ $dailyReportAssignUser
     */
    public function __construct(DailyReportAssignUser $dailyReportAssignUser, protected DailyAssignUserAnswer $dailyAssignUserAnswer, protected DailyReport $dailyReport)
    {
        parent::__construct($dailyReportAssignUser);
    }

    public function getDailyReports($request)
    {
        // 0 => new , 1 => started, 2 => under review , 3=> need edit, 4 => completed
        // in_progress => 0,1,3 ,
        // under_review => 2,
        // reviewed => 4
        if ($request->status == '1') {
            $request->status = ['0', '1', '3'];
        } else if ($request->status == '2') {
            $request->status = ['2'];
        } else {
            $request->status = ['4'];
        }

        $dailyReport = $this->model
            ->whereIn('status', $request->status)
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('dailyReport', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->when($request->area_id, function ($query) use ($request) {
                $query->where('area_id', $request->area_id);
            })
            ->latest()
            ->get();
        return $this->responseMsg('تمت العملية بنجاح', LeaderDailyReportAssignResource::collection($dailyReport));
    }

    public function dailyReportDetails($request, $id)
    {
        $dailyReport = $this->dailyReport->where('id', $id)->first();

        if (!$dailyReport) {
            return $this->responseMsg('لم يتم العثور على التقرير اليومي', null, 404);
        }

        // Pass user_id to the resource if it exists in the request
        return $this->responseMsg(
            'تمت العملية بنجاح',
            new LeaderDailyReportDetailsResource($dailyReport, $request->user_id ?? null)
        );
    }


    public function addDailyReportAssign($request)
    {
        $leader = auth('user')->user();
        $validated = $this->apiValidator($request->all(), [
            'daily_report_id' => 'required|exists:daily_reports,id',
            'user_id' => 'required',
            'deadline' => 'required|date',
            'axis_id' => 'required',
            'area_id' => 'required',
        ]);

        if ($validated) {
            return $validated;
        }


        $dailyReportAssign = $this->model->where('user_id', $request->user_id)
            ->where('daily_report_id', $request->daily_report_id)
//            ->where('status', '!=', '4')
            ->first();

        if ($dailyReportAssign) {
            return $this->responseMsg('تم تعيين هذا التقرير اليومي للمستخدم مسبقا', null, 400);
        }

        $inputs = $request->all();
        $inputs['leader_id'] = $leader->id;
        $dailyReport = $this->model->create($inputs);

        return $this->responseMsg('تمت اضافة المهمة اليومية بنجاح', LeaderDailyReportAssignResource::make($dailyReport));
    }

    public function actionQuestion($request)
    {
        $validated = $this->apiValidator($request->all(), [
            'daily_assign_user_answer_id' => 'required',
            'status' => 'required|in:0,1,2',
            'refuse_reason' => 'required_if:status,2',
            'refuse_notice' => 'nullable|string',
            'is_last_question' => 'required|in:0,1',
        ]);
        if ($validated) {
            return $validated;
        }

        $task_assign_question = $this->dailyAssignUserAnswer->where('id', $request->daily_assign_user_answer_id)->first();
        if (!$task_assign_question) {
            return $this->responseMsg('لم يتم العثور على السؤال', null, 404);
        }

        $task_assign_question->status = $request->status;
        $task_assign_question->refuse_reason = $request->status == 2 ? $request->refuse_reason : null;
        $task_assign_question->refuse_notice = $request->status == 2 ? $request->refuse_notice : null;
        $task_assign_question->save();

        // if question is last

        if ($request->is_last_question == 1) {


            $checkQuestionStatus = $this->dailyAssignUserAnswer
                ->where('daily_report_assign_user_id', $task_assign_question->daily_report_assign_user_id)
//                ->where('axis_question_id', $task_assign_question->axis_question_id)
                ->where('status', '2')->get();

            if ($checkQuestionStatus->count() > 0) {
//                dd($task_assign_question->daily_report_assign_user_id,$checkQuestionStatus->count());
                $dailyReportAssign = $this->model->find($task_assign_question->daily_report_assign_user_id);
                $dailyReportAssign->status = '3';
            } else {
                $dailyReportAssign = $this->model->find($task_assign_question->daily_report_assign_user_id);
                $dailyReportAssign->status = '4';

            }
            $dailyReportAssign->save();
            // update daily report status
        }

        return $this->responseMsg('تمت حفظ بيانات السؤال بنجاح', LeaderDailyReportQuestionAnswerResource::make($task_assign_question));
    }

    public function DailyReportRejectReason()
    {
        $cases = collect(DailyReportRejectReasonEnum::cases())->map(function ($case) {
            return [
                'id' => $case->value,
                'name' => $case->lang(),
            ];
        })->values();

        return $this->responseMsg('تمت العملية بنجاح', $cases);
    }
}
