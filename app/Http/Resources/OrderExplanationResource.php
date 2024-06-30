<?php

namespace App\Http\Resources;

use
    Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderExplanationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title_name' => $this->title ? $this->title->name : null,  // استخدام العلاقة الجديدة
            'class_room_name' => $this->subject_class ? $this->subject_class->classroom->name : null,
            'subject_name' => $this->subject_class ? $this->subject_class->subject->name : null,
            'note' => $this->note,
            'state' => $this->state,
        ];
    }
}
