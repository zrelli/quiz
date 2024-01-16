<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
    [
        [
            'id' => $this->id,
            'average_score' => $this->average_score,
            'average_spent_time' => $this->average_spent_time,
            'total_attempts' => $this->total_attempts,
            'is_successful' => $this->is_successful,
            'current_attempt_score' => $this->current_attempt_score,
            'current_attempt_spent_time' => $this->current_attempt_spent_time,
            'current_attempt_question' => $this->current_attempt_question,
            'current_attempt_start_at' => $this->current_attempt_start_at,
            'current_attempt_end_at' => $this->current_attempt_end_at,
            'current_attempt_is_closed' => $this->current_attempt_is_closed,
            'member_id' => $this->member_id,
            'quiz_id' => $this->quiz_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]
    ]
]  ;  }
}
