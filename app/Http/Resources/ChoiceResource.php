<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChoiceResource extends JsonResource
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
            'description' => $this->description,
            'explanation' => $this->explanation,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'question_id' => $this->question_id,
            $this->mergeWhen(isAdminApiRoute(), [
                'is_correct' => $this->is_correct
            ])
        ];
    }
}
