<?php
namespace App\Http\Controllers\Api\Member;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MemberQuizResource;
use App\Models\MemberQuiz;
use App\Repositories\MemberQuizRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\ResponseTrait;
class MemberExamController extends AppBaseController
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
        $exams = $this->memberQuizRepo->paginate(MEMBER_QUIZZES_PER_PAGE);
        $exams = MemberQuizResource::collection($exams);
        return $this->sendResponse($exams);
    }
    /**
     * Display the specified resource.
     */
    public function show(MemberQuiz $online_exam)
    {
        $online_exam = new MemberQuizResource($online_exam);
        return $this->sendResponse($online_exam);
    }
    /**
     * Display the specified resource.
     */
    public function startExam(MemberQuiz $online_exam)
    {
        $data = $online_exam->startExam();
        return $this->sendResponse($data);
    }
    // todo add validation and run action into repository
    public function lastTestStatistic(MemberQuiz $online_exam)
    {
        $online_exam->examStatistics()->first()->calculateFinalStatistics();
        $data = $online_exam->examStatistics;
        return $this->sendResponse($data);
    }
}
