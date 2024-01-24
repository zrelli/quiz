<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;

class QuestionResource extends JsonResource
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
            'question' => $this->question,
            'description' => $this->description,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'quiz_id' => $this->quiz_id,
            'updated_at' => $this->updated_at,
            $this->mergeWhen(Route::is('online-exams.questions.show'), [
                'choices' => ChoiceResource::collection($this->is_choices_randomly_ordered
                    ? $this->choices->shuffle()
                    : $this->choices),
            ]),
        ];
    }
}
