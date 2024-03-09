<?php
namespace App\Http\Controllers\Api\Admin;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Member;
use App\Models\Quiz;
use App\Repositories\QuizRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
use Illuminate\Support\Facades\Auth;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreQuizRequest::class);
        $quiz =  $this->quizRepo->store($input);
        return  new QuizResource($quiz);
    }
    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        return new QuizResource($quiz);
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
    public function toggleQuizPublishing(Quiz $quiz)
    {
        $canToggleQuizPublishingStatus =  Auth::user()->can('canToggleQuizPublishingStatus', $quiz);
        if ($canToggleQuizPublishingStatus) {
            $this->quizRepo->toggleQuizPublishing($quiz);
            return $this->sendSuccess('quiz publish status updated successfully');
        }
        return  $this->sendError('quiz publish status update failed');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $this->quizRepo->delete($quiz);
        return $this->sendSuccess('quiz deleted successfully');
    }
    public function increaseQuizAttempts(Quiz $quiz)
    {
        $quiz->max_attempts++;
        $quiz->save();
        return $this->sendSuccess('quiz updated successfully');
    }
    public function closeQuiz(Quiz $quiz)
    {
        $quiz->expired_at = now();
        $quiz->is_published = false;
        $quiz->save();
        return $this->sendSuccess('quiz updated successfully');
    }
    public function subscribeMemberToQuiz(Request $request, Quiz $quiz)
    {
        $member = Member::find($request->member_id);
        if (!$member) {
            return response()->json([
                'message' => 'Unauthorized',
                'success' => false
            ], 401);
        }
        if ($quiz->isExpired()) {
            return  $this->sendError('Exam date has been expired');
        }
        if ($quiz->isAlreadyAssigned($member->id)) {
            return  $this->sendError('You already subscribed');
        }
        $exam = $this->quizRepo->subscribeToQuiz($quiz, $member->id);
        return $this->sendResponse($exam);
    }
}
