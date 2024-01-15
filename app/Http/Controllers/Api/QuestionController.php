<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\Repositories\QuestionRepository;
use App\Repositories\QuizRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
class QuestionController extends AppBaseController
{
    use ResponseTrait, ApiRequestValidationTrait;
    /**
     * @var QuestionRepository
     */
    public $questionRepo;
    /**
     * UserController constructor.
     */
    public function __construct(QuestionRepository $questionRepository
    )
    {
        $this->questionRepo = $questionRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $this->questionRepo->setPaginationFilter(['quiz_id' => $id]);
        $questions = $this->questionRepo->paginate(QUESTIONS_PER_PAGE);
        $questions = QuestionResource::collection($questions);
        return $this->sendResponse($questions);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreQuestionRequest::class);
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $lastParameter ?? throw new ModelNotFoundException();
        $this->questionRepo->setRelationQuery(['quiz_id' => $lastParameter]);
        $question =  $this->questionRepo->store($input);
        $question = new QuestionResource($question);
        return $this->sendResponse($question);
    }
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $question =  $this->questionRepo->find($lastParameter);
        $question ?? throw new ModelNotFoundException();
        $question = new QuestionResource($question);
        return $this->sendResponse($question);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $input = $this->processRequest($request, UpdateQuestionRequest::class);
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $question =  $this->questionRepo->find($lastParameter);
        $question ?? throw new ModelNotFoundException();
        $this->questionRepo->update($input, $question);
        return $this->sendSuccess('question updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($question)
    {
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $question =  $this->questionRepo->find($lastParameter);
        $question ?? throw new ModelNotFoundException();
        $this->questionRepo->delete($question);
        return $this->sendSuccess('question deleted successfully');
    }
}
