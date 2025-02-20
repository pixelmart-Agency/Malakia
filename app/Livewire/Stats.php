<?php

namespace App\Livewire;

use App\Models\Area;
use App\Models\GeneralReport;
use App\Models\User;
use App\Models\ViolationReport;
use Livewire\Component;
use App\Models\DailyReportAssignUser; // تأكد من استيراد الموديل المناسب
use Carbon\Carbon;

class Stats extends Component
{
    public int $dailyReport = 0;
    public int $dailyGrowthPercentage = 0;

    public int $users = 0;
    public int $userGrowthPercentage = 0;

    public int $reports = 0;
    public int $reportGrowthPercentage = 0;

    public int $areas = 0;
    public int $areaGrowthPercentage = 0;

    public function mount()
    {
        $this->updateDailyReport();
        $this->updateUsers();
        $this->updateReports();
        $this->updateAreas();
    }

    public function updateDailyReport()
    {
        $dailyReportCount = DailyReportAssignUser::whereDate('created_at', Carbon::now())->count();
        $yesterdayDailyReport = DailyReportAssignUser::whereDate('created_at', Carbon::yesterday())->count();

        $this->dailyReport = $dailyReportCount;

        if ($yesterdayDailyReport > 0) {
            $this->dailyGrowthPercentage = (($dailyReportCount - $yesterdayDailyReport) / $yesterdayDailyReport) * 100;
        } else {
            $this->dailyGrowthPercentage = $dailyReportCount > 0 ? 100 : 0;
        }
    }

    public function updateUsers()
    {
        $todayUsers = User::whereDate('created_at', Carbon::today())->count();
        $yesterdayUsers = User::whereDate('created_at', Carbon::yesterday())->count();

        $this->users = $todayUsers; // إجمالي عدد المستخدمين

        // حساب نسبة الزيادة (تجنب القسمة على صفر)
        if ($yesterdayUsers > 0) {
            $this->userGrowthPercentage = (($todayUsers - $yesterdayUsers) / $yesterdayUsers) * 100;
        } else {
            $this->userGrowthPercentage = $todayUsers > 0 ? 100 : 0;
        }
    }


    public function updateReports()
    {
        $todayReports = GeneralReport::whereDate('created_at', Carbon::today())->count() + ViolationReport::whereDate('created_at', Carbon::today())->count();
        $yesterdayReports = GeneralReport::whereDate('created_at', Carbon::yesterday())->count() + ViolationReport::whereDate('created_at', Carbon::yesterday())->count();

        $this->reports = $todayReports;

        if ($yesterdayReports > 0) {
            $this->reportGrowthPercentage = (($todayReports - $yesterdayReports) / $yesterdayReports) * 100;
        } else {
            $this->reportGrowthPercentage = $todayReports > 0 ? 100 : 0;
        }
    }

    public function updateAreas()
    {
        $areaCount = Area::whereDate('created_at', Carbon::now())->count();
        $yesterdayArea = Area::whereDate('created_at', Carbon::yesterday())->count();

        $this->areas = $areaCount;

        if ($yesterdayArea > 0) {
            $this->areaGrowthPercentage = (($areaCount - $yesterdayArea) / $yesterdayArea) * 100;
        } else {
            $this->areaGrowthPercentage = $areaCount > 0 ? 100 : 0;
        }
    }


    public function render()
    {
        return view('livewire.stats');
    }
}

