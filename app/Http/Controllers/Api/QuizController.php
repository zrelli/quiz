<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Repositories\QuizRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\Request;
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
        $quizzes = QuizResource::collection($quizzes);
        return $this->sendResponse($quizzes);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreQuizRequest::class);
        $quiz =  $this->quizRepo->store($input);
        $quiz = new QuizResource($quiz);
        return $this->sendResponse($quiz);
    }
    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        $quiz = new QuizResource($quiz);
        return $this->sendResponse($quiz);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $input = $this->processRequest($request, UpdateQuizRequest::class);
        $this->quizRepo->update($input, $quiz);
        return $this->sendSuccess('quiz updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $this->quizRepo->delete($quiz);
        return $this->sendSuccess('quiz deleted successfully');
    }
    public function increaseQuizAttempts(Quiz $quiz){
        $quiz->max_attempts++;
        $quiz->save();
        return $this->sendSuccess('quiz updated successfully');
    }
    public function closeQuiz(Quiz $quiz){
        $quiz->expired_at = now();
        $quiz->is_expired = true;
        $quiz->save();
        return $this->sendSuccess('quiz updated successfully');
    }
}
