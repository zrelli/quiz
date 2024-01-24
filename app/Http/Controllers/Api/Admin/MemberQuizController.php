<?php
namespace App\Http\Controllers\Api\Admin;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\MemberQuizRequest;
use App\Http\Requests\StoreMemberQuizRequest;
use App\Http\Resources\MemberQuizResource;
use App\Models\MemberQuiz;
use App\Models\Quiz;
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
    public function index($id)
    {
        $this->memberQuizRepo->setPaginationFilter(['quiz_id' => $id]);
        $memberQuizzes = $this->memberQuizRepo->paginate(MEMBER_QUIZZES_PER_PAGE);
        return MemberQuizResource::collection($memberQuizzes);
    }
    public function show(Quiz $quiz, MemberQuiz $onlineExam)
    {
        if ($onlineExam->quiz_id != $quiz->id) {
            return $this->sendError('This exam not depend to current quiz.');
        }
        return new MemberQuizResource($onlineExam);
    }
}
