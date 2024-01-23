<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'max_attempts' => $this->max_attempts,
            'test_type' => $this->test_type,
            'is_published' => $this->is_published,
            'expired_at' => $this->expired_at,
            'started_at' => $this->started_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_subscribed' => $this->when(
                auth() && isMemberApiRoute(),
                fn () => $this->exams->where('member_id',  auth()->user()->id)->isNotEmpty()
            )
        ];
    }
}
