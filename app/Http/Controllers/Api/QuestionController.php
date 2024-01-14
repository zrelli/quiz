<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreUserRequest;
use App\Models\Question;
use App\Repositories\QuestionRepository;
use App\Traits\ApiRequestValidationTrait;
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
    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepo = $questionRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $this->questionRepo->setPaginationFilter(['quiz_id' => $id]);
        $question = $this->questionRepo->paginate(10);
        //todo set message
        return $this->sendResponse($question, '');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreUserRequest::class);
        $user =  $this->questionRepo->store($input);
        return $this->sendResponse($user, '');
    }
    /**
     * Display the specified resource.
     */
    public function show($question)
    {

        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $question =  $this->questionRepo->find($lastParameter);
        return $this->sendResponse($question, '');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $question)
    {
        $input = $request->all();
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $question =  $this->questionRepo->find($lastParameter);

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
        $this->questionRepo->delete($question);
        return $this->sendSuccess('question deleted successfully');
    }
}
