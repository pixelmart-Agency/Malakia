<?php

namespace App\Http\Resources;

use App\Enum\DailyReportRejectReasonEnum;
use App\Enum\NoticeStatusEnum;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int)$this->id,
            'title'=>$this->noticeType->name,
            'description' => $this->description,
            'lat' => $this->lat,
            'long' => $this->long,
            'date' => (new \DateTime($this->notice_date))->format('d F Y') . '، ' . (new \DateTime($this->notice_time))->format('H:i A'),
            'status' => (int)$this->status,
            'status_name' => NoticeStatusEnum::from($this->status)->lang(),
            'refuse_reason' => DailyReportRejectReasonEnum::from($this->refuse_reason)->lang(),
            'refuse_notice' => $this->refuse_notice,
            'user_id' => new UserResource($this->user),
            'notice_type' => new NoticeTypeResource($this->noticeType),
            'files' => MediaResource::collection($this->media),

        ];
    }
}
