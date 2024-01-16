<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\MemberQuizRequest;
use App\Http\Requests\StoreMemberQuizRequest;
use App\Http\Resources\MemberQuizResource;
use App\Models\MemberQuiz;
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
        $memberQuizzes = MemberQuizResource::collection($memberQuizzes);
        return $this->sendResponse($memberQuizzes);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreMemberQuizRequest::class);
        //AssignMemberToQuiz
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $lastParameter ?? throw new ModelNotFoundException();
        if (!isMyClient($input['member_id'])) {
            return  $this->sendError('Member is not in your platform');
        }
        $isAlreadyAssigned = $this->memberQuizRepo->isAlreadyAssigned($input['member_id'], $lastParameter);
        if ($isAlreadyAssigned) {
            return  $this->sendError('Member is already assigned');
        }
        $this->memberQuizRepo->setRelationQuery(['quiz_id' => $lastParameter]);
        $choice =  $this->memberQuizRepo->store($input);
        $choice = new MemberQuizResource($choice);
        return $this->sendResponse($choice);
    }
    /**
     * Display the specified resource.
     */
    public function show(MemberQuiz $memberQuiz)
    {
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $memberQuiz =  $this->memberQuizRepo->find($lastParameter);
        $memberQuiz ?? throw new ModelNotFoundException();
        $memberQuiz = new MemberQuizResource($memberQuiz);
        return $this->sendResponse($memberQuiz);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberQuiz $memberQuiz)
    {
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $memberQuiz =  $this->memberQuizRepo->find($lastParameter);
        $memberQuiz ?? throw new ModelNotFoundException();
        $this->memberQuizRepo->delete($memberQuiz);
        return $this->sendSuccess('Exam deleted successfully');
    }
}
