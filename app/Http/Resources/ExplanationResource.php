<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExplanationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title_name' => $this->relatedTitle ? $this->relatedTitle->name : null,  // استخدام العلاقة الجديدة
            'class_room_name' => $this->order_explanation && $this->order_explanation->subject_class ? $this->order_explanation->subject_class->classroom->name : null,
            'subject_name' => $this->order_explanation && $this->order_explanation->subject_class ? $this->order_explanation->subject_class->subject->name : null,
            'title' => $this->title,  // النص
            'note' => $this->note,
            'state' => $this->state,
            'video' => $this->video,
        ];
    }




}
