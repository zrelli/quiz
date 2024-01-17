<?php
namespace App\Http\Controllers\Api\Member;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\QuizResource;
use App\Models\Member;
use App\Models\Quiz;
use App\Repositories\QuizRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\ResponseTrait;
use Stancl\Tenancy\Database\TenantScope;
class MemberQuizController extends AppBaseController
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
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        $quiz = new QuizResource($quiz);
        return $this->sendResponse($quiz);
    }
    public function subscribeToQuiz(Quiz $quiz)
    {
        //deactivate  global tenant scope to show member record
        $member = Member::withoutGlobalScope(TenantScope::class)->where('user_id', auth()->user()->id)->first();
        $isAlreadyAssigned = $this->quizRepo->isAlreadyAssigned($member->id, $quiz->id);
        if ($isAlreadyAssigned) {
            return  $this->sendError('You already subscribed');
        }
        $exam = $this->quizRepo->subscribeToQuiz($quiz, $member->id);
        return $this->sendResponse($exam);
    }
}
