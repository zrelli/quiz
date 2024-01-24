<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use App\Models\Quiz;
use App\Repositories\QuestionRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;

class QuestionController extends AppBaseController
{
    use ResponseTrait, ApiRequestValidationTrait;
    /**
     * @var QuestionRepository
     */
    public $questionRepo;
    /**
     * UserController constructor.
     */
    public function __construct(
        QuestionRepository $questionRepository
    ) {
        $this->questionRepo = $questionRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Quiz $quiz)
    {
        $this->questionRepo->setPaginationFilter(['quiz_id' => $quiz->id]);
        $questions = $this->questionRepo->paginate(QUESTIONS_PER_PAGE);
        return QuestionResource::collection($questions);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Quiz $quiz)
    {
        $input = $this->processRequest($request, StoreQuestionRequest::class);
        $this->questionRepo->setRelationQuery(['quiz_id' => $quiz->id]);
        $question =  $this->questionRepo->store($input);
        return new QuestionResource($question);
    }
    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz, Question $question)
    {
        if ($question->quiz_id != $quiz->id) {
            return $this->sendError('This question not depend to current quiz.');
        }
        return new QuestionResource($question);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz, Question $question)
    {
        if ($question->quiz_id != $quiz->id) {
            return $this->sendError('This question not depend to current quiz.');
        }
        $input = $this->processRequest($request, UpdateQuestionRequest::class);
        $this->questionRepo->update($input, $question);
        return $this->sendSuccess('question updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz, Question $question)
    {
        if ($question->quiz_id != $quiz->id) {
            return $this->sendError('This question not depend to current quiz.');
        }
        $this->questionRepo->delete($question);
        return $this->sendSuccess('question deleted successfully');
    }
}
