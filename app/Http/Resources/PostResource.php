<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'user_id' => $this->user_id,
            'name' => $this->user->name,
            'image_profile' => $this->user->child_profile ? asset('storage/' . $this->user->child_profile->image) : null,
            'post_category' => $this->post_category,
            'state' => $this->state,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'text' => $this->text,
            'like_count' => $this->likes_count,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
