<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
class MemberResource extends JsonResource
{
    //todo
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'score' => $this->score,
            'time_taken' => $this->time_taken,
            'total_attempts' => $this->total_attempts,
            'successful_attempts' => $this->successful_attempts,
            'failed_attempts' => $this->failed_attempts,
        ];    }
}
