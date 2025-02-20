<?php

namespace App\Http\Resources\Leaders;

use App\Enum\DailyReportAssignUserStatusEnum;
use App\Http\Resources\MediaResource;
use App\Http\Resources\Users\AreaResource;
use App\Http\Resources\Users\UserResource;
use AnswerFile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderDailyReportQuestionAnswerResource extends JsonResource
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
            'daily_report_assign_user_id' => $this->daily_report_assign_user_id,
            'axis_question' => LeaderTasksQuestionResource::make($this->axisQuestion),
            'answer' => $this->answer,
            'question_answer' => $this->questionAnswer,
            'question_answers' => $this->axisQuestion->answers,
            'status' => $this->status,
            'refuse_reason' => $this->refuse_reason,
            'refuse_notice' => $this->refuse_notice,
            'answer_files' => MediaResource::collection($this->media),
        ];
    }
}
