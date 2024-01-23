<?php
namespace App\Http\Controllers\Api\Member;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MemberQuizResource;
use App\Models\Choice;
use App\Models\MemberQuiz;
use App\Models\Question;
use App\Repositories\MemberQuizRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
class MemberQuizController extends AppBaseController
{
    use ResponseTrait, ApiRequestValidationTrait;
    /**
     * @var MemberQuizRepository
     */
    public $memberQuizRepo;
    /**
     * UserController constructor.
     */
    public function __construct(MemberQuizRepository $memberQuizRepository)
    {
        $this->memberQuizRepo = $memberQuizRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->memberQuizRepo->setPaginationFilter(['member_id' => auth()->user()->id]);
        $exams = $this->memberQuizRepo->paginate(MEMBER_QUIZZES_PER_PAGE);
        return MemberQuizResource::collection($exams);
    }
    /**
     * Display the specified resource.
     */
    public function show(MemberQuiz $online_exam)
    {
        return new MemberQuizResource($online_exam);
    }
    /**
     * Display the specified resource.
     */
    public function startExam(MemberQuiz $online_exam)
    {
        $errors = $online_exam->canTakeExam();
        if (count($errors)) {
            return  $this->sendError($errors[0]);
        }
        $data = $online_exam->startExam();
        return $this->sendResponse($data);
    }
    // todo add validation and run action into repository
    public function lastTestStatistic(MemberQuiz $online_exam)
    {
        $online_exam->lastExamAttempt()->calculateFinalStatistics();
        $data = $online_exam->examStatistics();
        return $this->sendResponse($data);
    }
    public function answerQuestion(MemberQuiz $online_exam, Question $question, Request $request)
    {
        $questionIds = $online_exam->questions->pluck('id')->all(); //questions ordered by creation date
        $choice = Choice::where('id', '=', $request->choice_id)->first();
        $choiceIds = $question->choices->pluck('id')->all(); //
        $examTest = $online_exam->lastExamAttempt();
        if ($choice && in_array($choice->id, $choiceIds) && $examTest) {
            $currentQuestionsIndex = $examTest->current_question_index;
            if (count($questionIds) == $currentQuestionsIndex) {
                return $this->sendResponse(['exam' => 'completed'], 'You have answered all questions');
            }
            if ($questionIds[$currentQuestionsIndex] != $question->id) {
                return $this->sendError('You have chosen the wrong question');
            }
            $examTest->setAnswerStatus($choice->is_correct);
            return $this->sendResponse(['exam' => 'pending'], 'answer has been successfully submitted');
        }
        return  throw new ModelNotFoundException;
    }
}
