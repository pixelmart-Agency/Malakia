<?php

namespace App\Services\Api\Users;

use App\Enum\VehicleType;
use App\Http\Resources\BaseNoticeResource;
use App\Http\Resources\BaseViolationReportResource;
use App\Http\Resources\Leaders\TeamResource;
use App\Http\Resources\NoticeResource;
use App\Http\Resources\NoticeTypeResource;
use App\Http\Resources\Users\AreaResource;
use App\Http\Resources\Users\AttendanceResource;
use App\Http\Resources\Users\BaseDailyReportResource;
use App\Http\Resources\Users\UserDailyReportDetailsResource;
use App\Models\Notice;
use App\Models\NoticeType;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Resources\Users\DailyReportResource;
use App\Http\Resources\Users\UserResource;
use App\Http\Resources\ViolationReportResource;
use App\Models\Alert;
use App\Models\AlertUser;
use App\Models\Area;
use App\Models\AreaTeam;
use App\Models\Attendance;
use App\Models\Axis;
use App\Models\AxisQuestion;
use App\Models\DailyAssignUserAnswer;
use App\Models\DailyReport;
use App\Models\DailyReportAssignUser;
use App\Models\Media;
use App\Models\Setting;
use App\Models\User;
use App\Models\ViolationReport;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class UserService extends BaseService
{
    public function __construct(
        User                            $objModel,
        protected Attendance            $attendance,
        protected Alert                 $alert,
        protected AlertUser             $alertUser,
        protected Area                  $area,
        protected AreaTeam              $areaTeam,
        protected DailyReportAssignUser $dailyReportAssignUser,
        protected ViolationReport       $violationReport,
        protected DailyAssignUserAnswer $dailyAssignUserAnswer,
        protected AxisQuestion          $axisQuestion,
        protected Axis                  $axis,
        protected DailyReport           $dailyReport,
        protected Media                 $media,
        protected Notice                $notice,
        protected NoticeType            $noticeType,

    )
    {
        parent::__construct($objModel);
    }


    public function checkin($request)
    {
        $user = auth('user')->user();

        $validator = $this->apiValidator($request->all(), [
            'checkin_lat' => 'required',
            'checkin_long' => 'required',
        ]);

        if ($validator) {
            return $validator;
        }

        if (!$this->isCheckinTimeInvalid()) {
            return $this->responseMsg('لا يمكن تسجيل الحضور في الوقت الحالي', null, 422);
        }

        if ($this->hasPendingCheckout($user->id)) {
            return $this->responseMsg('يجب تسجيل الخروج اولا', null, 422);
        }

        $request['user_id'] = $user->id;
        $request['checkin'] = Carbon::now();
        $request['date'] = Carbon::now()->format('Y-m-d');

        $attendance = $this->attendance->create($request->all());

        $this->sendCheckinNotification($attendance);

        return $this->responseMsg('تم تسجيل الحضور', new AttendanceResource($attendance), 201);
    }

    private function isCheckinTimeInvalid()
    {
        $setting = Setting::query()->first();
        $checkinDate = Carbon::parse($setting->checkin_date);
        $checkinMaxDate = Carbon::parse($setting->checkin_max_date);


        return Carbon::now()->gt($checkinDate) && Carbon::now()->lt($checkinMaxDate);
    }

    private function hasPendingCheckout($userId)
    {
        return Attendance::query()
            ->where('user_id', $userId)
            ->where('date', Carbon::now()->format('Y-m-d'))
            ->where('checkout', null)
            ->exists();
    }

    private function sendCheckinNotification($attendance)
    {
        $notificationData = [
            'title' => 'تسجيل حضور',
            'body' => 'قام ' . $attendance->user->full_name . ' بتسجيل حضور في ' . now()->format('g:i A'),
        ];

        // Add logic to send notification
    }

    public function checkout($request)
    {
        $validator = $this->apiValidator($request->all(), [
            'checkout_long' => 'required',
            'checkout_lat' => 'required',
        ]);

        if ($validator) {
            return $validator;
        }
        $user = auth('user')->user();


        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', now()->format('Y-m-d'))
            ->whereNull('checkout')
            ->first();

        if (!$attendance) {
            return $this->responseMsg('يجب تسجيل الدخول قبل الخروج', null, 422);
        }

        $attendance->update([
            'checkout' => Carbon::now(),
            'checkout_long' => $request->checkout_long,
            'checkout_lat' => $request->checkout_lat,
        ]);

        $notificationData = [
            'title' => 'تسجيل انصراف',
            'body' => 'قام ' . $attendance->user->full_name . ' بتسجيل انصراف في ' . now()->format('g:i A'),
        ];

        // Add logic to send notification

        return $this->responseMsg('تم تسجيل الانصراف', new AttendanceResource($attendance));
    }


    public function myAreas()
    {
        $user = Auth::guard('user')->user();
        $areaIds = $this->areaTeam->where('user_id', $user->id)->pluck('area_id');
        $areas = $this->area->whereIn('id', $areaIds)->get();

        return $this->responseMsg('Areas', AreaResource::collection($areas));

    }


    public function storeFcm($request)
    {
        $validator = $this->apiValidator($request->all(), [
            'fcm_token' => 'required',
        ]);

        if ($validator) {
            return $validator;
        }
        $user = User::where('id', Auth::guard('user')->user()->id)->first();
        $user->fcm_token = $request->fcm_token;
        $user->save();
        return $this->responseMsg('Fcm Token Saved Successfully', UserResource::make(Auth::guard('user')->user()));
    }


    public function logout()
    {
        Auth::guard('user')->logout();
        JWTAuth::invalidate(Auth::guard('user')->user()->jwt_token);
        return $this->responseMsg('تم تسجيل الخروج', null);
    }


    public function home()
    {
        $user = Auth::guard('user')->user();

        $dailyReportsUsers = $this->dailyReportAssignUser->where('user_id', $user->id)->get();
        $areaId = $this->areaTeam->where('user_id', $user->id)->first()->area_id;
        $team = $this->getTeamMembers($areaId, $user->id);
        $violationReports = $this->violationReport->where('user_id', $user->id)
            ->where('user_type', '1')
            ->get();


        $reports = $this->dailyReport->whereIn('id', $dailyReportsUsers->pluck('daily_report_id'))->take(3)->get()->map(function ($report) use ($dailyReportsUsers) {
            $report->status = $dailyReportsUsers->where('daily_report_id', $report->id)->first()->status;
            $report->deadline = $dailyReportsUsers->where('daily_report_id', $report->id)->first()->deadline;
            $dailyReportsQuestions = $this->axisQuestion->where('axis_id', $dailyReportsUsers->where('daily_report_id', $report->id)->first()->axis_id)->count();

            $questionsAnswered = $this->dailyAssignUserAnswer->where('daily_report_assign_user_id', $dailyReportsUsers->where('daily_report_id', $report->id)->first()->id)
                ->where('user_id', $dailyReportsUsers->where('daily_report_id', $report->id)->first()->user_id)
                ->count();
            $report->progress = $dailyReportsQuestions > 0 ? ($questionsAnswered / $dailyReportsQuestions) * 100 : 0;
            $report->area = $this->area->where('id', $dailyReportsUsers->where('daily_report_id', $report->id)->first()->area_id)->first();

            return $report;
        });
        $UserAttendanceToday = $this->attendance->where('user_id', $user->id)
            ->whereDate('created_at', now()->format('Y-m-d'))->first();

        if ($UserAttendanceToday) {
            if ($UserAttendanceToday->checkout == null) {
                // User has checked in but not checked out
                $attendanceStatus = 0;
            } else {
                $attendanceStatus = 1;
            }
        } else {
            $attendanceStatus = 2;
        }

        $setting = Setting::query()->first();

        $data = [
            'user' => new UserResource($user),
            'attendanceStatus' => $attendanceStatus,
            'checkin_date' => Carbon::parse($setting->checkin_date)->format('Y-m-d H:i:s.u'),
            'checkin_max_date' => Carbon::parse($setting->checkin_max_date)->format('Y-m-d H:i:s.u'),
            'checkout_date' => Carbon::parse($setting->checkout_date)->format('Y-m-d H:i:s.u'),
            'checkout_max_date' => Carbon::parse($setting->checkout_max_date)->format('Y-m-d H:i:s.u'),

//            'leader' => $this->getAreaLeader($areaId),
            'alertCount' => $this->getAlertCount($user->id),
            'dailyReportsCount' => $dailyReportsUsers->count(),
            'newDailyReportsCount' => $dailyReportsUsers->where('status', '1')->count(),
            'notStartedDailyReportsCount' => $dailyReportsUsers->where('status', '0')->count(),
            'completedDailyReportsCount' => $dailyReportsUsers->where('status', '2')->count(),
            'lateDailyReportsCount' => $dailyReportsUsers->where('status', '!=', '4')->where('deadline', '<', Carbon::now())->count(),
            'violationReportsCount' => $violationReports->count(),
            'myDailyReports' => BaseDailyReportResource::collection($reports),
            'teams' => TeamResource::collection($team->take(3)),
        ];

        return $this->responseMsg('تمت العملية بنجاح', $data);
    }

    private function getTeamMembers($areaId, $userId)
    {
        $areaTeam = $this->areaTeam->where('area_id', $areaId);
        return $this->model->whereIn('id', $areaTeam->pluck('user_id'))
            ->where('id', '!=', $userId)
            ->get();
    }

//    private function getAreaLeader($areaId)
//    {
//        if (!$areaId) {
//            return null;
//        }
//        $area = $this->area->where('id', $areaId)->first();
//        return UserResource::make($area->leader);
//    }

    private function getAlertCount($userId)
    {
        return $this->alertUser->where('user_id', $userId)
            ->where('seen', 0)
            ->count();
    }


    public function getAllMyDailyReports($request)
    {
        $user = Auth::guard('user')->user();
        $dailyReportsUsers = $this->dailyReportAssignUser->where('user_id', $user->id)
            ->when(isset($request->status), function ($query) use ($request) {
                if ($request->status == '5') {
                    // 5 is late and late is stared and this deadline is passed


                    $query->where('status', '1')->where('deadline', '<', Carbon::now());


                } else {
                    $query->where('status', $request->status);

                }
            })
            ->when($request->date, function ($query) use ($request) {
                $query->whereDate('created_at', $request->date);
            })
            ->when($request->area_id, function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->where('area_id', $request->area_id);
                });
            })
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('dailyReport', function ($query) use ($request) {
                    $query->where('title', 'like', '%' . $request->search . '%');
                });
            })
            ->latest()
            ->get();

//        if ($dailyReportsUsers->isEmpty()) {
//            $dailyReportsUsers = $this->dailyReportAssignUser->where('user_id', $user->id)->get();
//        }

        $reports = $this->dailyReport->whereIn('id', $dailyReportsUsers->pluck('daily_report_id'))->get()->map(function ($report) use ($dailyReportsUsers) {
            $dailyReportUser = $dailyReportsUsers->where('daily_report_id', $report->id)->first();
            $report->status = $dailyReportUser->status;
            $report->deadline = $dailyReportUser->deadline;
            $report->area = $this->area->find($dailyReportUser->area_id);

            $dailyReportsQuestions = $this->axisQuestion->where('axis_id', $dailyReportUser->axis_id)->count();

            $questionsAnswered = $this->dailyAssignUserAnswer->where('daily_report_assign_user_id', $dailyReportUser->id)
                ->where('user_id', $dailyReportUser->user_id)
                ->count();
            $report->progress = $dailyReportsQuestions > 0 ? ($questionsAnswered / $dailyReportsQuestions) * 100 : 0;


            return $report;
        });

        return $this->responseMsg('تمت العملية بنجاح', BaseDailyReportResource::collection($reports));
    }
//    public function getDailyReportWithFilter($request)
//    {
//        $user = Auth::guard('user')->user();
//        $dailyReportsIds = $this->dailyReportAssignUser->where('user_id', $user->id)
//
//            ->when($request->status, function ($query) use ($request) {
//                $query->where('status', $request->status);
//            })
//            ->when($request->date, function ($query) use ($request) {
//                $query->whereDate('created_at', $request->date);
//            })
//            ->when($request->area_id, function ($query) use ($request) {
//                $query->whereHas('user', function ($query) use ($request) {
//                    $query->where('area_id', $request->area_id);
//                });
//            })
//            ->latest()->pluck('daily_report_id');
//        $dailyReports = $this->dailyReport->whereIn('id', $dailyReportsIds)->get();
//        return $this->responseMsg('تمت العملية بنجاح', DailyReportResource::collection($dailyReports));
//    }

//public function getMyDailyReportsWithSearch ($request)
//{
//    $user = Auth::guard('user')->user();
//    $dailyReportsIds = $this->dailyReportAssignUser->where('user_id', $user->id)
//        ->when($request->search, function ($query) use ($request) {
//            $query->whereHas('dailyReport', function ($query) use ($request) {
//                $query->where('title', 'like', '%' . $request->search . '%');
//            });
//        })
//        ->latest()
//        ->latest()->pluck('daily_report_id');
//    $dailyReports=$this->dailyReport->whereIn('id', $dailyReportsIds)->get();
//
//    return $this->responseMsg('تمت العملية بنجاح', DailyReportResource::collection($dailyReports));
//}

    public function MyDailyReportsDetails($id)
    {

        $dailyReportAssignUser = $this->dailyReportAssignUser->where('daily_report_id', $id)->where('user_id', Auth::guard('user')->user()->id)->first();
        $dailyReport = $this->dailyReport->where('id', $dailyReportAssignUser->daily_report_id)->first();
        $dailyReportUser = $dailyReportAssignUser->where('daily_report_id', $dailyReport->id)->first();
        $dailyReport->status = $dailyReportUser->status;
        $dailyReport->deadline = $dailyReportUser->deadline;
        $dailyReport->area = $this->area->find($dailyReportUser->area_id);

        $dailyReport->dailyReportsQuestions = $this->axisQuestion->where('axis_id', $dailyReportUser->axis_id);
        $dailyReport->questionsAnswered = $this->dailyAssignUserAnswer->where('user_id', $dailyReportUser->user_id)->where('daily_report_assign_user_id', $dailyReportUser->id);
        $dailyReport->progress = $dailyReport->dailyReportsQuestions->count() > 0 ? ($dailyReport->questionsAnswered->count() / $dailyReport->dailyReportsQuestions->count()) * 100 : 0;


        if (!$dailyReportAssignUser) {

            return $this->responseMsg('لا يمكن العثور على التقرير', null, 404);
        }
        return $this->responseMsg('تمت العملية بنجاح',
            new UserDailyReportDetailsResource($dailyReport, auth('user')->user()->id ?? null)
        );

    }


//    public function storeAnswersInDailyReport($request)
//    {
//        // Validation
//        $validator = $this->apiValidator($request->all(), [
//            'daily_report_id' => 'required|exists:daily_reports,id',
//            'axis_question_ids' => 'required|array',
//            'axis_question_ids.*' => 'exists:axis_questions,id',
//            'question_answer_ids' => 'nullable|array',
//            'question_answer_ids.*' => 'exists:question_answers,id',
//            'answers' => 'nullable|array',
//            'answers.*' => 'string',
//            'files' => 'nullable|array',
//            'files.*' => 'array',
//            'files.*.*' => 'file',
//            'is_last_question' => 'required|in:0,1'
//        ]);
//
//        if ($validator) {
//            return $validator;
//        }
//
//        $this->dailyReportAssignUser->where('daily_report_id', $request->daily_report_id)
//            ->where('user_id', Auth::guard('user')->user()->id)
//            ->first()
//            ->update(['status' => '1']);
//
//        for ($i = 0; $i < count($request->axis_question_ids); $i++) {
//            // Check if user answered this question before
//            $existingAnswer = $this->dailyAssignUserAnswer
//                ->where('daily_report_assign_user_id', $this->dailyReportAssignUser
//                    ->where('daily_report_id', $request->daily_report_id)
//                    ->where('user_id', Auth::guard('user')->user()->id)->first()->id)
//                ->where('axis_question_id', $request->axis_question_ids[$i])
//                ->where('user_id', Auth::guard('user')->user()->id)
//                ->first();
//
//            if ($existingAnswer) {
//                if ($request->is_last_question == 1) {
//                    $this->dailyReportAssignUser
//                        ->where('daily_report_id', $request->daily_report_id)
//                        ->where('user_id', Auth::guard('user')->user()->id)->update([
//                            'status' => '2'
//                        ]);
//
//
//                }
//                $existingAnswer->update([
//                    'daily_report_assign_user_id' => $this->dailyReportAssignUser
//                        ->where('daily_report_id', $request->daily_report_id)
//                        ->where('user_id', Auth::guard('user')->user()->id)
//                        ->first()->id,
//                    'axis_question_id' => $request->axis_question_ids[$i],
//                    'answer' => $request->answers[$i] ?? null,
//                    'question_answer_id' => $request->question_answer_ids[$i] ?? null,
//                    'user_id' => Auth::guard('user')->user()->id,
//                    'status' => '0',
//                ]);
//
//                // Delete old files if they exist
//                $oldFiles = $this->media->where('model_type', 'App\Models\DailyAssignUserAnswer')
//                    ->where('model_id', $existingAnswer->id)
//                    ->get();
//                if ($oldFiles->count() > 0) {
//                    $this->deleteOldFiles($oldFiles);
//                }
//
//                // Check if files are uploaded for this question
//                if (isset($request->file('files')[$i])) {
//                    $this->storeFiles($request->file('files')[$i], $existingAnswer->id);
//                }
//            } else {
//                $newAnswer = $this->dailyAssignUserAnswer->create([
//                    'daily_report_assign_user_id' => $this->dailyReportAssignUser
//                        ->where('daily_report_id', $request->daily_report_id)
//                        ->where('user_id', Auth::guard('user')->user()->id)
//                        ->first()->id,
//                    'axis_question_id' => $request->axis_question_ids[$i],
//                    'answer' => $request->answers[$i] ?? null,
//                    'question_answer_id' => $request->question_answer_ids[$i] ?? null,
//                    'user_id' => Auth::guard('user')->user()->id,
//                    'status' => '0',
//                ]);
//
//                // Check if files are uploaded for this question
//                if (isset($request->file('files')[$i])) {
//                    $this->storeFiles($request->file('files')[$i], $newAnswer->id);
//                }
//            }
//        }
//        //edited by mohamed
//
//        return $this->responseMsg('تمت العملية بنجاح', 200);
//    }


    public function storeAnswersInDailyReport($request)
    {
        // Validation
        $validator = $this->apiValidator($request->all(), [
            'daily_report_id' => 'required|exists:daily_reports,id',
            'axis_question_ids' => 'required|array',
            'axis_question_ids.*' => 'exists:axis_questions,id',
            'question_answer_ids' => 'nullable|array',
            'question_answer_ids.*' => 'exists:question_answers,id',
            'answers' => 'nullable|array',
            'answers.*' => 'string',
            'files' => 'nullable|array',
            'files.*' => 'array',
            'files.*.*' => 'file',
            'is_last_question' => 'required|in:0,1'
        ]);

        if ($validator) {
            return $validator;
        }

        $dailyReportAssignUser = $this->dailyReportAssignUser->where('daily_report_id', $request->daily_report_id)
            ->where('user_id', Auth::guard('user')->user()->id)
            ->first();
        $dailyReportAssignUser->update(['status' => '1']);

        for ($i = 0; $i < count($request->axis_question_ids); $i++) {
            // Check if user answered this question before
            $existingAnswer = $this->dailyAssignUserAnswer
                ->where('daily_report_assign_user_id', $dailyReportAssignUser->id)
                ->where('axis_question_id', $request->axis_question_ids[$i])
                ->where('user_id', Auth::guard('user')->user()->id)
                ->first();

            if ($existingAnswer) {
                $existingAnswer->update([
                    'daily_report_assign_user_id' => $dailyReportAssignUser->id,
                    'axis_question_id' => $request->axis_question_ids[$i],
                    'answer' => $request->answers[$i] ?? null,
                    'question_answer_id' => $request->question_answer_ids[$i] ?? null,
                    'user_id' => Auth::guard('user')->user()->id,
                    'status' => '0',
                ]);

                // Delete old files if they exist
                $oldFiles = $this->media->where('model_type', 'App\Models\DailyAssignUserAnswer')
                    ->where('model_id', $existingAnswer->id)
                    ->get();
                if ($oldFiles->count() > 0) {
                    $this->deleteOldFiles($oldFiles);
                }

                // Check if files are uploaded for this question
                if (isset($request->file('files')[$i])) {
                    $this->storeFiles($request->file('files')[$i], $existingAnswer->id);
                }
            } else {
                $newAnswer = $this->dailyAssignUserAnswer->create([
                    'daily_report_assign_user_id' => $dailyReportAssignUser->id,
                    'axis_question_id' => $request->axis_question_ids[$i],
                    'answer' => $request->answers[$i] ?? null,
                    'question_answer_id' => $request->question_answer_ids[$i] ?? null,
                    'user_id' => Auth::guard('user')->user()->id,
                    'status' => '0',
                ]);

                // Check if files are uploaded for this question
                if (isset($request->file('files')[$i])) {
                    $this->storeFiles($request->file('files')[$i], $newAnswer->id);
                }
            }
        }

        if ($request->is_last_question == 1) {
            $dailyReportAssignUser->update(['status' => '2']);
        }

        return $this->responseMsg('تمت العملية بنجاح', 200);
    }
    public function deleteOldFiles($oldFiles)
    {

        foreach ($oldFiles as $file) {

            $file->delete();
        }


    }


    public function storeFiles($files, $id)
    {
        if ($files instanceof \Symfony\Component\HttpFoundation\FileBag) {
            $files = $files->all();
        }

        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if (!$file instanceof \Illuminate\Http\UploadedFile) {
                continue;
            }

            $filePath = $this->handleFile($file, 'userAnswer');
            $this->media->create([
                'file' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_name' => $file->getClientOriginalName(),
                'model_type' => 'App\Models\DailyAssignUserAnswer',
                'model_id' => $id
            ]);
        }
    }
//
//    private function updateTaskAnswer($taskAssign, $taskAnswer, $request)
//    {
//        $taskAnswer->update([
//            'task_assign_id' =>$taskAssign->id,
//            'task_question_id' => $request->task_question_id,
//            'question_answer_id' => $request->question_answer_id,
//            'user_id' => Auth::guard('user')->user()->id,
//        ]);
//
//        $this->updateTaskAnswerFiles($taskAssign, $request);
//    }
//
//    private function createTaskAnswer($taskAssign, $request)
//    {
//        $taskAssign->taskAnswers()->create([
//            'task_assign_id' => $taskAssign->id,
//            'task_question_id' => $request->task_question_id,
//            'question_answer_id' => $request->question_answer_id,
//            'user_id' => Auth::guard('user')->user()->id,
//        ]);
//
//        $this->createTaskAnswerFiles($taskAssign, $request);
//    }
//
//    private function updateTaskAnswerFiles($taskAssign, $request)
//    {
//        if ($taskAssign->taskAnswerFiles()->where('task_question_id', $request->task_question_id)->exists()) {
//            $taskAssign->taskAnswerFiles()->where('task_question_id', $request->task_question_id)->delete();
//        }
//
//        if (isset($request->files) && $request->hasFile('files')) {
//            $files = $request->file('files');
//            $filesType = $request->filesType;
//
//            foreach ($files as $file) {
//                foreach ($filesType as $fileType) {
//                    $taskAssign->taskAnswerFiles()->create([
//                        'task_assign_id' => $taskAssign->id,
//                        'task_question_id' => $request->task_question_id,
//                        'file' => $file,
//                        'file_type' => $fileType,
//                    ]);
//                }
//            }
//        }
//    }
//
//    private function createTaskAnswerFiles($taskAssign, $request)
//    {
//        if (isset($request->files) && $request->hasFile('files')) {
//            $files = $request->file('files');
//            $filesType = $request->filesType;
//
//            foreach ($files as $file) {
//                foreach ($filesType as $fileType) {
//
//
//                    $taskAssign->taskAnswerFiles()->create([
//                        'task_assign_id' => $taskAssign->id,
//                        'task_question_id' => $request->task_question_id,
//                        'file' =>  $this->handleFile($request->image, 'tasks_answers'),
//                        'file_type' => $fileType,
//                    ]);
//                }
//            }
//        }
//    }
//
//
//    public function getAllMyQuestionnaires()
//    {
//        $user = Auth::guard('user')->user();
//        $questionnairesAssignIds = $this->taskAssign->where('user_id', $user->id)->pluck('task_id');
//        $questionnaires = $this->task->whereIn('id', $questionnairesAssignIds)->where('type', '1')->get();
//
//        return $this->responseMsg('تمت العملية بنجاح', DailyReportResource::collection($questionnaires));
//    }
//
//
//    public function getMyQuestionnairesWithFilter($request)
//    {
//        $user = Auth::guard('user')->user();
//        $questionnairesAssignIds = $this->taskAssign->where('user_id', $user->id)
//            ->whereHas('task', function ($query) {
//                $query->where('type', '1');
//            })
//            ->when($request->status, function ($query) use ($request) {
//                $query->where('status', $request->status);
//            })
//            ->when($request->date, function ($query) use ($request) {
//                $query->whereDate('created_at', $request->date);
//            })
//            ->latest()->pluck('task_id');
//
//        $questionnaires = $this->task->whereIn('id', $questionnairesAssignIds)->get();
//        return $this->responseMsg('تمت العملية بنجاح', DailyReportResource::collection($questionnaires));
//    }
//
//    public function myQuestionnaireDetails($id)
//    {
//        $questionnaire = $this->taskAssign->where('task_id', $id)->where('user_id', Auth::guard('user')->user()->id)->first();
//        return $this->responseMsg('تمت العملية بنجاح', DailyReportDetailResource::make($questionnaire));
//
//    }
//
//    public function StoreOrUpdateAnswerQuestionInQuestionnaire($request)
//    {
//        $validator = $this->apiValidator($request->all(), [
//            'questionnaire_id' => 'required',
//            'questionnaire_question_id' => 'required',
//            'question_answer_id' => 'required',
//        ]);
//
//        if ($validator) {
//            return $validator;
//        }
//
//
//        $questionnaireAssign = $this->taskAssign->where('task_id', $request->questionnaire_id)->where('user_id', Auth::guard('user')->user()->id)->first();
//        if (!$questionnaireAssign) {
//
//            return $this->responseMsg('لا يمكن العثور على الاستبيان', null, 404);
//        }
//        $questionnaire = $this->task->findOrFail($questionnaireAssign->task_id);
//
//        $questionnaireAnswer = $questionnaireAssign->taskAnswers()->where('task_question_id', $request->questionnaire_question_id)->first();
//
//        $message = $questionnaireAnswer ? 'تم التعديل بنجاح' : 'تمت العملية بنجاح';
//
//        if ($questionnaireAnswer) {
//            $this->updateQuestionnaireAnswer($questionnaireAssign, $questionnaireAnswer, $request);
//        } else {
//            $this->createQuestionnaireAnswer($questionnaireAssign, $request);
//        }
//
//        return $this->responseMsg($message, null);
//
//    }
//
//    private function updateQuestionnaireAnswer($questionnaireAssign, $questionnaireAnswer, $request)
//    {
//        $questionnaireAnswer->update([
//            'task_assign_id' =>$questionnaireAssign->id,
//            'task_question_id' => $request->questionnaire_question_id,
//            'question_answer_id' => $request->question_answer_id,
//            'user_id' => Auth::guard('user')->user()->id,
//        ]);
//
//    }
//
//    private function createQuestionnaireAnswer($questionnaireAssign, $request)
//    {
//        $questionnaireAssign->taskAnswers()->create([
//            'task_assign_id' => $questionnaireAssign->id,
//            'task_question_id' => $request->questionnaire_question_id,
//            'question_answer_id' => $request->question_answer_id,
//            'user_id' => Auth::guard('user')->user()->id,
//        ]);
//
//    }
//
    public function getMyProfile()
    {
        $user = Auth::guard('user')->user();
        return $this->responseMsg('تمت العملية بنجاح', UserResource::make($user));

    }

//    public function UpdateOrDeleteMyProfileImage($request)
//    {
//        $validator = $this->apiValidator($request->all(), [
//            'image' => 'nullable',
//        ]);
//
//        if ($validator) {
//            return $validator;
//        }
//
//        $user = Auth::guard('user')->user();
//        if (isset($request->image) && $request->hasFile('image')) {
//            $user->image = $this->handleFile($request->image, 'users');
//        }else{
//            $user->image = null;
//        }
//        $user->save();
//
//        return $this->responseMsg('تمت العملية بنجاح', UserResource::make($user));
//    }
//
    public function getMyAttendances($request)
    {
        $validator = $this->apiValidator($request->all(), [
            'from' => 'nullable|date_format:Y-m-d',
            'to' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator) {
            return $validator;
        }

        $user = Auth::guard('user')->user();
        if ($request->from && $request->to) {
            $attendances = $this->attendance->where('user_id', $user->id)
                ->whereBetween('date', [$request->from, $request->to])
                ->latest()->get()->groupBy('date');
            return $this->responseMsg('تمت العملية بنجاح', AttendanceResource::collection($attendances->flatten()));
        }
        $attendances = $this->attendance->where('user_id', $user->id)->latest()->get()->groupBy('date');
        return $this->responseMsg('تمت العملية بنجاح', AttendanceResource::collection($attendances->flatten()));

    }


    // in new version
    public function addViolationReport($request)
    {
        $validator = $this->apiValidator($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'reference_number' => 'required|unique:violation_reports,reference_number',
//            'reference_number' => 'required|numeric',
            'violation_time' => 'required|date_format:H:i',
            'violation_date' => 'required|date_format:d-m-Y',
            'map_url' => 'required|url',
            'lat' => 'required',
            'long' => 'required',
            'plate_number' => 'required',
            'plate_letter_ar' => 'required|string',
            'plate_letter_en' => 'required|string',
            'plate_image' => 'nullable|image',
            'vehicle_type' => 'required|in:' . implode(',', array_map(fn($e) => $e->value, VehicleType::cases())),
            'violation_image' => 'nullable|image',
            'violation_video' => 'nullable|mimes:mp4,avi,mov',


        ]);

        if ($validator) {
            return $validator;
        }
        $user = Auth::guard('user')->user();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $data['user_type'] = '0';

        if ($request->hasFile('plate_image')) {
            $data['plate_image'] = $this->handleFile($request->file('plate_image'), 'Violation_Report');
        }

        if ($request->hasFile('violation_image')) {
            $data['violation_image'] = $this->handleFile($request->file('violation_image'), 'Violation_Report');
        }

        if ($request->hasFile('violation_video')) {
            $data['violation_video'] = $this->handleFile($request->file('violation_video'), 'Violation_Report');
        }


        $obj = $this->violationReport->create($data);
        if ($obj) {


            return $this->responseMsg('تمت العملية بنجاح', new  ViolationReportResource($obj));
        }

        return $this->responseMsg('حدث خطا ما', 404);

    }


    public function getMyViolationReports()
    {
        $user = Auth::guard('user')->user();
        $myViolationReports = $this->violationReport->where('user_id', $user->id)->where('user_type', '0')->get();
        return $this->responseMsg('تمت العملية بنجاح', BaseViolationReportResource::collection($myViolationReports));

    }

    public function getMyViolationReportDetails($id)
    {
        $obj = $this->violationReport->find($id);
        if (!$obj) {
            return $this->responseMsg('لم يتم العثور على التقرير', null, 404);
        }

        return $this->responseMsg('تمت العملية بنجاح', new  ViolationReportResource($obj));


    }


    public function exportMultipleViolationReports(Request $request)
    {
        $request->validate([
            'violation_report_ids' => 'required|array',
            'violation_report_ids.*' => 'integer|exists:violation_reports,id',
        ]);

        $violations = ViolationReport::whereIn('id', $request->violation_report_ids)->get();

        if ($violations->isEmpty()) {
            return response()->json(['message' => 'لا يوجد تقارير'], 404);
        }

        $fileName = 'reports_' . time() . '.xlsx';
        $filePath = 'exports/' . $fileName;
        if (!file_exists(dirname(storage_path('app/public/' . $filePath)))) {
            mkdir(dirname(storage_path('app/public/' . $filePath)), 0777, true);
        }

        $spreadsheet = new Spreadsheet();
        $sheetIndex = 0;

        foreach ($violations as $violation) {
            if ($sheetIndex > 0) {
                $spreadsheet->createSheet();
            }
            $spreadsheet->setActiveSheetIndex($sheetIndex);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Report ' . $violation->id); // Set sheet name

            $headers = [
                'ID', 'Reference Number', 'Violation Time', 'Violation Date',
                'Map URL', 'Latitude', 'Longitude', 'Plate Number',
                'Plate Letter (AR)', 'Plate Letter (EN)', 'Vehicle Type',
                'Title', 'Description', 'Created At'
            ];

            $sheet->fromArray([$headers], null, 'A1');

            $data = [
                $violation->id,
                $violation->reference_number,
                $violation->violation_time,
                $violation->violation_date,
                $violation->map_url,
                $violation->lat,
                $violation->long,
                $violation->plate_number,
                $violation->plate_letter_ar,
                $violation->plate_letter_en,
                $violation->vehicle_type,
                $violation->title,
                $violation->description,
                $violation->created_at->format('d-m-Y H:i'),
            ];

            $sheet->fromArray([$data], null, 'A2');

            $sheetIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $filePath));
        $fullPathUrl= url('storage/' . $filePath);

        return $this->responseMsg('تمت العملية بنجاح', ['file' => $fullPathUrl]);
    }


    public function getMyTeamMembers()
    {
        $user = Auth::guard('user')->user();
        $areaId = $this->areaTeam->where('user_id', $user->id)->pluck('area_id');
        $myTeamMembers = $this->areaTeam->whereIn('area_id', $areaId)->pluck('user_id');
        $users = $this->model->whereIn('id', $myTeamMembers)->get();
        return $this->responseMsg('تمت العملية بنجاح', UserResource::collection($users));
    }

    public function noticeTypes()
    {
        $noticeTypes = $this->noticeType->get();
        return $this->responseMsg('تمت العملية بنجاح', NoticeTypeResource::collection($noticeTypes));

    }

    public function storeNotice($request)
    {
        $validator = $this->validateNoticeRequest($request);
        if ($validator) {
            return $validator;
        }

        $data = $this->prepareNoticeData($request);
        $obj = $this->notice->create(Arr::except($data, 'files'));

        if ($request->hasFile('files')) {
            $this->saveNoticeFiles($request->file('files'), $obj->id);
        }

        return $obj ? $this->responseMsg('تمت العملية بنجاح', new NoticeResource($obj))
            : $this->responseMsg('حدث خطا ما', 404);
    }

    private function validateNoticeRequest($request)
    {
        return $this->apiValidator($request->all(), [
            'notice_type_id' => 'required|exists:notice_types,id',
            'lat' => 'required',
            'leader_id' => 'required|exists:users,id',
            'description' => 'required',
            'long' => 'required',
            'notice_time' => 'required|date_format:H:i',
            'notice_date' => 'required|date_format:d-m-Y',
            'files' => 'nullable|array',
            'files.*.*' => 'file',
        ]);
    }

    private function prepareNoticeData($request)
    {
        $user = Auth::guard('user')->user();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $data['status'] = '0';


        return $data;
    }

    private function saveNoticeFiles($files, $noticeId)
    {
        foreach ($files as $file) {
            $this->media->create([
                'file' => $this->handleFile($file, 'notices'),
                'file_type' => $file->getClientOriginalExtension(),
                'file_name' => $file->getClientOriginalName(),
                'model_type' => 'App\Models\Notice',
                'model_id' => $noticeId
            ]);
        }
    }

    public function getMyNotices($request)
    {
        $notices = $this->notice->where('user_id', Auth::guard('user')->user()->id)
            ->where('status', $request->status)
            ->get();
        return $this->responseMsg('تمت العملية بنجاح', BaseNoticeResource::collection($notices));

    }

    public function getMyNoticeDetails($id)
    {

        $notice = $this->notice->find($id);

        if (!$notice) {

            return $this->responseMsg('هذه الشكوى غير موجودة');
        }

        return $this->responseMsg('تم تحميل الشكوي بنجاح', new NoticeResource($notice));


    }

    public function getMyLeaders()
    {
        $user = Auth::guard('user')->user();
        $areaIds = $this->areaTeam->where('user_id', $user->id)->pluck('area_id');
        $leaders = $this->model->whereIn('id', function ($query) use ($areaIds) {
            $query->select('user_id')
                ->from('area_teams')
                ->whereIn('area_id', $areaIds)
                ->where('type', 1);
        })->get();
        return $this->responseMsg('تمت العملية بنجاح', UserResource::collection($leaders));
    }

}
