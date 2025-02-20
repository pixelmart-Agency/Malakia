<?php

namespace App\Http\Resources\Leaders;

use App\Enum\DailyReportAssignUserStatusEnum;
use App\Enum\LeaderDailyReportAssignUserStatusEnum;
use App\Enum\monitorType;
use App\Enum\SideType;
use App\Http\Resources\Users\AreaResource;
use App\Http\Resources\Users\AxisQuestionResource;
use App\Http\Resources\Users\AxisResource;
use App\Http\Resources\Users\DailyReportQuestionResource;
use App\Models\AxisQuestion;
use App\Models\DailyReportAssignUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderDailyReportAssignDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->dailyReport->title,
            'description' => $this->dailyReport->description,
            'status' => (int)$this->status,
            'status_name' => LeaderDailyReportAssignUserStatusEnum::from($this->status)->lang(),
            'created_at' => $this->created_at->format('d F Y'),
            'updated_at' => $this->updated_at->format('d F Y'),
            'monitor_type' => monitorType::from($this->dailyReport->monitor_type)->lang(),
            'side_type' => SideType::from($this->dailyReport->side_type)->lang(),
            'axis' => new AxisResource($this->axis),
            'area' => new AreaResource($this->area),
            'deadline' => $this->deadline,
            'daily_report_questions' => AxisQuestionResource::collection($this->questions),
        ];
    }
}
