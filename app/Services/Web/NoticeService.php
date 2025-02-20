<?php

namespace App\Services\Web;

use App\Enum\DailyReportRejectReasonEnum;
use App\Enum\ReportStatusEnum;
use App\Models\Notice as ObjModel;

use App\Models\NoticeType;
use App\Services\BaseService;

class NoticeService extends BaseService
{
//    protected string $folder = 'admin/admin';
//    protected string $route = 'adminHome';

    public function __construct(
        protected ObjModel   $objModel,
        protected NoticeType $noticeType
    )
    {
        parent::__construct($objModel);
    }

    public function index()
    {
        return view('web.notice.index');
    }

    public function indexDatatable($dataTable)
    {
        return $dataTable->render('web.notice.index');
    }

    public function storeNoticeType($request)
    {


        $validator = $this->apiValidator($request->all(), [
            'name' => 'required',
            'priority' => 'required|in:' . implode(',', ['suggest', 'low', 'mid', 'high']),
            'period' => 'required|in:' . implode(',', ['none', 'after 24 hours', 'after 48 hours', 'live']),
            'level' => 'required|in:' . implode(',', ['لم يتم التصعيد', 'اذا لم يعالج', 'تصعيد مباشر']),
        ]);

        if ($validator) {
            return $validator;
        }
        try {

            $noticeType = $this->noticeType->create([
                'name' => $request->name,
                'priority' => $request->priority,
                'period' => $request->period,
                'level' => $request->level,
            ]);
//            dd($noticeType);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء نوع البلاغ بنجاح',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false,
                'message' => 'حدث خطأ ما أثناء إنشاء نوع البلاغ',
                'error' => $e->getMessage()], 500);
        }

    }

    public function show($id)
    {
        $notice = $this->objModel->find($id);
        $refuseReasons = array_map(fn($reason) => strtolower(str_replace('_', ' ', $reason->name)), DailyReportRejectReasonEnum::cases());
        return view('web.notice.details', ['notice' => $notice,'refuse_reasons'=>$refuseReasons]);
    }


    public function updateNoticeStatus($request, $id)
    {
        try {
            $notice = $this->objModel->find($id);

            if (!$notice) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم إيجاد التقرير',
                ], 404);
            }
            if ($notice->status != 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ غير متوقع',
                ], 404);
            }
            if ($request->status == 'accept') {
//                dd('ksjdfh');
                $notice->update([
                    'status' => '1',
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'تم إعتماد بنجاح'
                ], 200);
            } elseif ($request->status == 'refuse') {
                $reportStatus = $notice->update([
                    'status' => ReportStatusEnum::REJECTED->value,
                    'refuse_notes' => $request->refuse_notes,
                    'refuse_reason' => $request->refuse_reason,
                ]);
                if ($reportStatus) {
                    return response()->json([
                        'success' => true,
                        'message' => 'تم رفض بنجاح'
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'حدث خطأ ما أثناء تحديث حالة التقرير',
                    ], 500);
                }

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ ما أثناء تحديث حالة التقرير',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ ما أثناء تحديث حالة التقرير',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
