<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreChoiceRequest;
use App\Http\Requests\UpdateChoiceRequest;
use App\Http\Resources\ChoiceResource;
use App\Models\Choice;
use App\Models\Question;
use App\Models\Quiz;
use App\Repositories\ChoiceRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;

class ChoiceController extends AppBaseController
{
    use ResponseTrait, ApiRequestValidationTrait;
    /**
     * @var ChoiceRepository
     */
    public $choiceRepo;
    /**
     * UserController constructor.
     */
    public function __construct(ChoiceRepository $choiceRepository)
    {
        $this->choiceRepo = $choiceRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Quiz $quiz, Question $question)
    {
        if ($question->quiz_id != $quiz->id) {
            return $this->sendError('This question not depend to current quiz.');
        }
        $this->choiceRepo->setPaginationFilter(['question_id' => $question->id]);
        $choices = $this->choiceRepo->paginate(CHOICES_PER_PAGE);
        return ChoiceResource::collection($choices);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Quiz $quiz, Question $question,)
    {
        if ($question->quiz_id != $quiz->id) {
            return $this->sendError('This question not depend to current quiz.');
        }
        $input = $this->processRequest($request, StoreChoiceRequest::class);
        $this->choiceRepo->setRelationQuery(['question_id' => $question->id]);
        $choice =  $this->choiceRepo->store($input);
        $choice = new ChoiceResource($choice);
        return $this->sendResponse($choice);
    }
    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz, Question $question, Choice $choice)
    {
        if ($question->quiz_id != $quiz->id) {
            return $this->sendError('This question not depend to current quiz.');
        }
        if ($choice->question_id != $question->id) {
            return $this->sendError('This choice not depend to current question.');
        }
        return new ChoiceResource($choice);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz, Question $question, Choice $choice)
    {
        if ($question->quiz_id != $quiz->id) {
            return $this->sendError('This question not depend to current quiz.');
        }
        if ($choice->question_id != $question->id) {
            return $this->sendError('This choice not depend to current question.');
        }
        $input = $this->processRequest($request, UpdateChoiceRequest::class);
        $this->choiceRepo->update($input, $choice);
        return $this->sendSuccess('choice updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz, Question $question, Choice $choice)
    {
        if ($question->quiz_id != $quiz->id) {
            return $this->sendError('This question not depend to current quiz.');
        }
        if ($choice->question_id != $question->id) {
            return $this->sendError('This choice not depend to current question.');
        }
        $this->choiceRepo->delete($choice);
        return $this->sendSuccess('choice deleted successfully');
    }
}
