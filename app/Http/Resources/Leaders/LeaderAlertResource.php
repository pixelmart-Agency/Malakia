<?php

namespace App\Http\Resources\Leaders;

use App\Http\Resources\MediaResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderAlertResource extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'to'=>$this->to,
            'created_at' => Carbon::parse($this->created_at)->locale('ar')->translatedFormat('j F Y، g:i A'),
            'priority' => $this->priority,
            'files' => $this->media ? MediaResource::collection($this->media) : [] //ddd
        ];
    }
}
