<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Repositories\QuizRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\ResponseTrait;

class QuizController extends AppBaseController
{
    use ResponseTrait, ApiRequestValidationTrait;
    /**
     * @var QuizRepository
     */
    public $quizRepo;
    /**
     * QuizController constructor.
     */
    public function __construct(QuizRepository $quizRepository)
    {
        $this->quizRepo = $quizRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = $this->quizRepo->paginate(QUIZZES_PER_PAGE);
        return QuizResource::collection($quizzes);
    }
    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        return new QuizResource($quiz->loadCount('questions'));
    }
    public function subscribeToQuiz(Quiz $quiz)
    {
        if ($quiz->isExpired()) {
            return  $this->sendError('Exam date has been expired');
        }
        if ($quiz->isAlreadyAssigned()) {
            return  $this->sendError('You already subscribed');
        }
        $exam = $this->quizRepo->subscribeToQuiz($quiz);
        return $this->sendResponse($exam);
    }
}
