<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\QuestionResource;
use App\Models\MemberQuiz;
use App\Models\Question;
use App\Repositories\QuestionRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\ResponseTrait;

class MemberExamQuestionController extends AppBaseController
{
    use ResponseTrait, ApiRequestValidationTrait;
    /**
     * @var QuestionRepository
     */
    public $questionRepo;
    /**
     * UserController constructor.
     */
    public function __construct(QuestionRepository $questionRepo)
    {
        $this->questionRepo = $questionRepo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(MemberQuiz $online_exam)
    {
        $quiz = $online_exam->quiz;
        $questions = $this->questionRepo->all(['quiz_id' => $quiz->id]);
        return QuestionResource::collection($questions);
    }
    /**
     * Display the specified resource.
     */
    public function show(MemberQuiz $online_exam, Question $question)
    {
        return new QuestionResource($question);
    }
}
