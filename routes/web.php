<?php

use App\Http\Controllers\web\ActivityLogController;
use App\Http\Controllers\web\AreaController;
use App\Http\Controllers\web\AreaLocationController;
use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\AxisManagementController;
use App\Http\Controllers\web\DailyReportController;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\web\NoticeController;
use App\Http\Controllers\web\UserController;
use App\Http\Controllers\web\NotificationController;
use App\Http\Controllers\web\ReportController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;


;
Route::get('/check', function () {
    return view('task-details');
});
Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('login', [AuthController::class, 'index'])->name('admin.login');
    Route::POST('login', [AuthController::class, 'login'])->name('admin.login');
    Route::POST('sendOtp', [AuthController::class, 'sendOtp'])->name('admin.sendOtp');
    Route::get('forget_password', [AuthController::class, 'forgetPassword'])->name('admin.forget_password');
    Route::POST('new_password', [AuthController::class, 'newPassword'])->name('admin.new_password');
    Route::POST('updatePassword', [AuthController::class, 'updatePassword'])->name('admin.update_password');
    Route::POST('checkOtp', [AuthController::class, 'checkOtp'])->name('admin.checkOtp');
    // dashboard elements
    Route::group(['middleware' => AdminMiddleware::class], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('adminHome');
        Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
        Route::get('/axes-management', [AxisManagementController::class, 'index'])->name('axesManagement');
        Route::get('/area_location/{id}', [AreaLocationController::class, 'index'])->name('areaLocation');
        Route::get('/axes-management/index-datatable', [AxisManagementController::class, 'indexDatatable'])->name('axesManagement.datatable');
        // _------------------------------------------------------------------------------------------------------------------------------------------
        Route::get('users/index-datatable', [UserController::class, 'indexDatatable'])->name('users.datatable');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/admin/users/{id}/edit-status', [UserController::class, 'editStatus'])->name('users.updateStatus');
        Route::resource('users', UserController::class)->except('edit');
        // ------------------------------------------------------------------------------------------------------------------------------------------
        Route::get('/area', [AreaController::class, 'index'])->name('area');
        Route::get('/area/index-datatable', [AreaController::class, 'indexDatatable'])->name('area.datatable');

        Route::get('/area_location/{id}', [AreaLocationController::class, 'index'])->name('areaLocation');
        Route::get('/axes-management/create', [AxisManagementController::class, 'create'])->name('axesManagement.create');
        Route::post('/axes-management', [AxisManagementController::class, 'store'])->name('axesManagement.store');
//        Route::get('/report-details', [ReportController::class, 'index'])->name('report.index');
        Route::get('/notifications', [notificationController::class, 'index'])->name('notification.index');
        Route::get('/axes-management/index-datatable', [AxisManagementController::class, 'indexDatatable'])->name('axesManagement.datatable');
        Route::get('/area', [AreaController::class, 'index'])->name('area');
        Route::post('area', [AreaController::class, 'store'])->name('area.store');
        Route::get('/area/index-datatable', [AreaController::class, 'indexDatatable'])->name('area.datatable');
//        Route::get('/activities', [ActivityController::class, 'index'])->name('activity.index');

        // daily report routes
        Route::get('/daily_report/index-datatable', [DailyReportController::class, 'indexDatatable'])->name('daily_report.datatable');
        Route::get('/daily_report_assign_user/index-datatable', [DailyReportController::class, 'index2Datatable'])->name('daily_report_assign_user.datatable');

        Route::get('/daily_report/{daily_report_id}', [DailyReportController::class, 'show'])->name('daily_report.show');
        Route::get('/daily_report_assign_user/{daily_report_assign_user_id}', [DailyReportController::class, 'showDailyReportAssignUser'])->name('daily_report_assign_user.show');

        Route::get('/daily_report', [DailyReportController::class, 'index'])->name('daily_report.index');
        Route::post('/daily_report/store', [DailyReportController::class, 'store'])->name('daily_report.store');

        // daily report routes

        //reports (violation/general)
        Route::get('/general_report/index-datatable', [ReportController::class, 'generalReportIndexDatatable'])->name('general_report.datatable');
        Route::get('/violation_report/index-datatable', [ReportController::class, 'violationReportIndexDatatable'])->name('violation_report.datatable');
        Route::get('/general_report/{general_report_id}',[ReportController::class,'generalReportShow'])->name('general_report.show');
        Route::get('/violation_report/{violation_report_id}',[ReportController::class,'violationReportShow'])->name('violation_report.show');
        Route::get('/report',[ReportController::class,'index'])->name('report.index');
        Route::put('/report/{report_id}/update_status',[ReportController::class,'updateReportStatus'])->name('report_status.update');



        //notice البلاغات
        Route::get('/notice/index-datatable', [NoticeController::class, 'noticeIndexDatatable'])->name('notice.datatable');
        Route::get('/notice_type/index-datatable', [NoticeController::class, 'noticeTypeIndexDatatable'])->name('notice_type.datatable');
        Route::post('/notice_type', [NoticeController::class, 'storeNoticeType'])->name('notice_type.store');
        Route::resource('/notice',NoticeController::class);
        Route::put('/notice/{notice_id}/update_status',[NoticeController::class,'updateNoticeStatus'])->name('notice_status.update');

//activity log
        Route::get('activity_logs/index-datatable', [ActivityLogController::class, 'indexDatatable'])->name('activity_logs.datatable');
        Route::get('activity_logs', [ActivityLogController::class, 'index'])->name('activity_logs.index');
        Route::delete('activity_logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity_logs.destroy');

    });

});
