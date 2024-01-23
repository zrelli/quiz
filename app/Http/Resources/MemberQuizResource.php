<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class MemberQuizResource extends JsonResource
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
            'score' => $this->score,
            'time_taken' => $this->time_taken,
            'total_attempts' => $this->total_attempts,
            'is_successful' => $this->is_successful,
            'member_id' => $this->member_id,
            'quiz_id' => $this->quiz_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_expired' => $this->quiz->isExpired(),


            $this->mergeWhen(Route::is('online-exams.show'), [
                'questions' => QuestionResource::collection($this->questions),

            ]),

        ];
    }
}
