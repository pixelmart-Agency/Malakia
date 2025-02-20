<?php

/**
 * Summary of namespace App\Services\Api\Leader
 */

namespace App\Services\Api\Leader;

use App\Enum\DeleteReasonEnum;
use App\Enum\VehicleType;
use App\Http\Resources\AlertResource;
use App\Http\Resources\BaseGeneralReportResource;
use App\Http\Resources\BaseViolationReportResource;
use App\Http\Resources\GeneralReportResource;
use App\Http\Resources\Leaders\LeaderAlertResource;
use App\Http\Resources\Leaders\LeaderDailyReportAssignResource;
use App\Http\Resources\Leaders\LeaderResource;
use App\Http\Resources\Leaders\TeamResource;
use App\Http\Resources\MediaResource;
use App\Http\Resources\NoticeResource;
use App\Http\Resources\PrivacyPolicyResource;
use App\Http\Resources\ViolationReportResource;
use App\Models\Alert;
use App\Models\AlertUser;
use App\Models\AreaTeam;
use App\Models\GeneralReport;
use App\Models\Media;
use App\Models\Notice;
use App\Models\PolicyPrivacy;
use App\Models\User;
use App\Models\ViolationReport;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Summary of LeaderService
 */
class LeaderService extends BaseService
{
    /**
     * Summary of __construct
     * @param \App\Models\User $objModel
     */
    public function __construct(User                      $objModel, protected PolicyPrivacy $policyPrivacy,
                                protected AreaTeam        $areaTeam,
                                protected Alert           $alert,
                                protected AlertUser       $alertUser,
                                protected Media           $media,
                                protected ViolationReport $violationReport,
                                protected GeneralReport   $generalReport,
                                protected Notice          $notice,
    )
    {
        parent::__construct($objModel);
    }


    /**
     * Summary of home
     * @return \Illuminate\Http\JsonResponse
     */
    public function home(): \Illuminate\Http\JsonResponse
    {
        $leader = auth('user')->user();
        $reportsReviewed = $leader->leaderReports()->where('status', '4')->count();
        $reportsUnderReview = $leader->leaderReports()->where('status', '2')->latest()->get();
        $leaderSuggestCount = $leader->leaderNotices()->whereHas('noticeType', function ($notice) {
            return $notice->where('priority', 'suggest');
        })->count();

        $leaderNoticesCount = $leader->leaderNotices()->whereHas('noticeType', function ($notice) {
            return $notice->where('priority', '!=', 'suggest');
        })->count();
        // Get all areas of the user

        $areaIds = $leader->areas()->pluck('area_id');
        $teamIds = $this->areaTeam->whereIn('area_id', $areaIds)->pluck('user_id');
        $teams = $this->model->whereIn('id', $teamIds)->get();

        return response()->json([
            'message' => 'تمت العملية بنجاح',
            'teams' => TeamResource::collection($teams),
            'reportsReviewedCount' => $reportsReviewed,
            'reportsUnderReviewCount' => $reportsUnderReview->count(),
            'reportsUnderReview' => LeaderDailyReportAssignResource::collection($reportsUnderReview),
            'leaderSuggestCount' => $leaderSuggestCount,
            'leaderNoticesCount' => $leaderNoticesCount

        ]);
    }


    /**
     * Summary of storeFcm
     * @param mixed $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
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
        return $this->responseMsg('تم حفظ التوكن الخاص بالتطبيق', null);
    }


    public function getAlerts()
    {
        $leader = auth('user')->user();
        $alerts = $this->alert->where('leader_id', $leader->id)->get();
        return $this->responseMsg('تمت العملية بنجاح', LeaderAlertResource::collection($alerts));

    }

    public function addAlert($request)
    {
        $leader = auth('user')->user();
        $areaIds = $leader->areas()->pluck('area_id');
        $teamIds = $this->areaTeam->whereIn('area_id', $areaIds)->pluck('user_id');
        $validator = $this->apiValidator($request->all(), [
            'title' => 'required',
            'body' => 'required',
            'to' => 'required|in:0,1',
            'priority' => 'required|in:low,mid,high',
        ]);

        if ($validator) {
            return $validator;
        }

        $newAlert = new $this->alert();
        $newAlert->title = $request->title;
        $newAlert->body = $request->body;
        $newAlert->leader_id = $leader->id;
        $newAlert->to = $request->to;
        $newAlert->priority = $request->priority;
        if ($newAlert->save()) {
            if ($request->has('files')) {
                $this->storeFiles($request->file('files'), $newAlert->id);
            }
            if ($newAlert->to == '1') {
                foreach ($teamIds as $user_id) {
                    $newAlert->alertUsers()->create([
                        'user_id' => $user_id
                    ]);
                }
            }
            return $this->responseMsg('تم اضافة التنبيه بنجاح', LeaderAlertResource::make($newAlert));
        }

        return $this->responseMsg('هناك خطا في الاضافة حاول مره اخري', null, 422);
    }

    public function alertDetails($id)
    {
        $alert = $this->alert->where('id', $id)->first();

        if (!$alert) {
            return $this->responseMsg('هذا التنبيه غير موجود', null, 404);
        }

        return $this->responseMsg('تمت العملية بنجاح', LeaderAlertResource::make($alert));
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

            $filePath = $this->handleFile($file, 'alert');
            $this->media->create([
                'file' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_name' => $file->getClientOriginalName(),
                'model_type' => 'App\Models\Alert',
                'model_id' => $id
            ]);
        }
    }


    public function getMyViolationOrGeneralReports($request)
    {

        $user = Auth::guard('user')->user();
        if ($request->type == 0) {
            $myViolationReports = $this->generalReport->where('leader_id', $user->id)->get();
            return $this->responseMsg('تمت العملية بنجاح', BaseGeneralReportResource::collection($myViolationReports));
        }
        $myViolationReports = $this->violationReport->where('user_id', $user->id)->where('user_type', '1')->get();
        return $this->responseMsg('تمت العملية بنجاح', BaseViolationReportResource::collection($myViolationReports));

    }

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
        $data['user_type'] = '1';

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

    public function getMyViolationGeneralReportDetails($request, $id)
    {
        $obj = $request->type == 0
            ? $this->generalReport->find($id)
            : $this->violationReport->find($id);

        if (!$obj) {
            return $this->responseMsg('لم يتم العثور على التقرير', null, 404);
        }

        $resource = $request->type == 0
            ? new GeneralReportResource($obj)
            : new ViolationReportResource($obj);

        return $this->responseMsg('تمت العملية بنجاح', $resource);
    }


    public function addGeneralReport($request)
    {
        $validator = $this->apiValidator($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'extra' => 'nullable',
            'files' => 'nullable|array',
            'files.*.*' => 'file',


        ]);

        if ($validator) {
            return $validator;
        }
        $user = Auth::guard('user')->user();
        $data = $request->all();
        $data['leader_id'] = $user->id;


        $obj = $this->generalReport->create($data);
        if ($obj) {
            if ($request->has('files')) {
                $this->storeGeneralReportFiles($request->file('files'), $obj->id);
            }
            return $this->responseMsg('تمت العملية بنجاح', new  GeneralReportResource($obj));
        }

        return $this->responseMsg('حدث خطا ما', 404);

    }

    public function storeGeneralReportFiles($files, $id)
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

            $filePath = $this->handleFile($file, 'general_report');
            $this->media->create([
                'file' => $filePath,
                'file_type' => $file->getClientOriginalExtension(),
                'file_name' => $file->getClientOriginalName(),
                'model_type' => 'App\Models\GeneralReport',
                'model_id' => $id
            ]);
        }
    }

    public function exportMultipleGeneralOrViolationReports($request)
    {
        $validator = $this->apiValidator($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'required|numeric',
            'type' => 'required|in:0,1'
        ]);

        if ($validator) {
            return $validator;
        }

        $ids = $request->ids;
        $type = $request->type;

        if ($type == 0) {
            $reports = $this->generalReport->whereIn('id', $ids)->get();
        } else {
            $reports = $this->violationReport->whereIn('id', $ids)->get();
        }

        $fileName = 'reports_' . time() . '.xlsx';
        $filePath = 'exports/' . $fileName;
        if (!file_exists(dirname(storage_path('app/public/' . $filePath)))) {
            mkdir(dirname(storage_path('app/public/' . $filePath)), 0777, true);
        }
        $this->exportReports($reports, $type, $filePath);
        $fullPathUrl = url('storage/' . $filePath);

        return $this->responseMsg('تمت العملية بنجاح', ['file' => $fullPathUrl]);


    }

    public function exportReports($reports, $type, $filePath)
    {
        $spreadsheet = new Spreadsheet();
        $sheetIndex = 0;

        foreach ($reports as $report) {
            if ($sheetIndex > 0) {
                $spreadsheet->createSheet();
            }
            $spreadsheet->setActiveSheetIndex($sheetIndex);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Report ' . $report->id);

            $headers = [
                'ID', 'Title', 'Description', 'Created At'
            ];

            if ($type == 1) {
                $headers = array_merge($headers, [
                    'Reference Number', 'Violation Time', 'Violation Date',
                    'Map URL', 'Latitude', 'Longitude', 'Plate Number',
                    'Plate Letter (AR)', 'Plate Letter (EN)', 'Vehicle Type',
                ]);
            }

            $sheet->fromArray([$headers], null, 'A1');

            $data = [
                $report->id,
                $report->title,
                $report->description,
                $report->created_at->format('d-m-Y H:i'),
            ];

            if ($type == 1) {
                $data = array_merge($data, [
                    $report->reference_number,
                    $report->violation_time,
                    $report->violation_date,
                    $report->map_url,
                    $report->lat,
                    $report->long,
                    $report->plate_number,
                    $report->plate_letter_ar,
                    $report->plate_letter_en,
                    $report->vehicle_type,
                ]);
            }

            $sheet->fromArray([$data], null, 'A2');

            $sheetIndex++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/' . $filePath));

        return $filePath;
    }


    public function getNotices($request)
    {
        $leader_id = auth('user')->user()->id;
        $notices = $this->notice->where('leader_id', $leader_id)
            ->when($request->has('status'), function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->latest()->get();
        return $this->responseMsg('تمت العملية بنجاح', NoticeResource::collection($notices));
    }

    public function getNotice($id)
    {
        return $this->responseMsg('تمت العملية بنجاح', new NoticeResource($this->notice->find($id)));
    } // end of getNotice

    public function actionNotice($request)
    {
        $validator = $this->apiValidator($request->all(), [
            'id' => 'required',
            'status' => 'required|in:1,2',
            'refuse_reason' => 'required_if:status,2',
            'refuse_notice' => 'nullable',
        ]);

        if ($validator) {
            return $validator;
        }

        $notice = $this->notice->find($request->id);
        $notice->status = $request->status;
        $notice->refuse_reason = $request->refuse_reason;
        $notice->refuse_notice = $request->refuse_notice;
        $notice->save();
        return $this->responseMsg('تمت العملية بنجاح', new NoticeResource($notice));
    }


    /**
     * Summary of logout
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('user')->logout();
        JWTAuth::invalidate(Auth::guard('user')->user()->jwt_token);
        return $this->responseMsg('تم تسجيل الخروج', null);
    }
}
