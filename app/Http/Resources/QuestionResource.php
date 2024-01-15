<?php
namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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
            'updated_at' => $this->updated_at,
        ];
    }
}
// $table->id();
// // $table->foreignId('tenant_id');// todo we will add global scope to show only current tenant questions
// $table->unsignedBigInteger('quiz_id');
// $table->foreign('quiz_id')->on('quizzes')->references('id')->onDelete('cascade');
// $table->string('question');
// $table->string('slug')->unique();
// $table->text('description')->nullable();
// $table->boolean('is_choices_randomly_ordered')->default(true);
// $table->boolean('has_multiple_answers')->default(false); // we will set all false
// $table->timestamps();