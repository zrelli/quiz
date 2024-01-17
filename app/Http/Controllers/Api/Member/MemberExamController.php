<?php
namespace App\Http\Controllers\Api\Member;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreQuestionAnswerRequest;
use App\Http\Resources\MemberQuizResource;
use App\Models\Choice;
use App\Models\MemberExamStatistics;
use App\Models\MemberQuiz;
use App\Models\Question;
use App\Repositories\MemberQuizRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\ResponseTrait;
use Illuminate\Http\Request;
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
        //create test
        // $online_exam->startExam();
        //get all tests
        // $data= $online_exam->examStatistics();
        $data = $online_exam->startExam();
        //    foreach ($data as $d){
        //     // $d->setResultStatus(2,0);
        //     $d->setResultStatus(2,0);
        //     $d->calculateFinalStatistics();
        //    }
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
