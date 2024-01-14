<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreUserRequest;
use App\Models\MemberQuiz;
use App\Repositories\MemberQuizRepository;
use App\Traits\ApiRequestValidationTrait;
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
        $this->memberQuizRepo->setPaginationFilter(['quiz_id'=>$id]);
        $users = $this->memberQuizRepo->paginate(10);
        //todo set message
        return $this->sendResponse($users, '');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreUserRequest::class);
        $user =  $this->memberQuizRepo->store($input);
        return $this->sendResponse($user, '');
    }
    /**
     * Display the specified resource.
     */
    public function show(MemberQuiz $memberQuiz)
    {
        return $this->sendResponse($memberQuiz, '');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemberQuiz $memberQuiz)
    {
        $input = $request->all();
        $this->memberQuizRepo->update($input, $memberQuiz);
        return $this->sendSuccess('choice updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberQuiz $memberQuiz)
    {
        $this->memberQuizRepo->delete($memberQuiz);
        return $this->sendSuccess('choice deleted successfully');
    }
}
