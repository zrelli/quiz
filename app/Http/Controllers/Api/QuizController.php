<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreQuizRequest;
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
        $quizzes = $this->quizRepo->paginate(10);
        //todo set message
        return $this->sendResponse($quizzes, '');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreQuizRequest::class);
        $Quiz =  $this->quizRepo->store($input);
        return $this->sendResponse($Quiz, '');
    }
    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        return $this->sendResponse($quiz, '');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $Quiz)
    {
        $input = $request->all();
        $this->quizRepo->update($input, $Quiz);
        return $this->sendSuccess('Quiz updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $Quiz)
    {
        $this->quizRepo->delete($Quiz);
        return $this->sendSuccess('Quiz deleted successfully');
    }
}
