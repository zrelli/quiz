<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreUserRequest;
use App\Models\Choice;
use App\Repositories\ChoiceRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
class ChoiceController extends AppBaseController
{
    use ResponseTrait, ApiRequestValidationTrait;
    /**
     * @var ChoiceRepository
     */
    public $choiceRepo;
    /**
     * UserController constructor.
     */
    public function __construct(ChoiceRepository $choiceRepository)
    {
        $this->choiceRepo = $choiceRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $this->choiceRepo->setPaginationFilter(['question_id' => $lastParameter]);
        $users = $this->choiceRepo->paginate(10);
        //todo set message
        return $this->sendResponse($users, '');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreUserRequest::class);
        $user =  $this->choiceRepo->store($input);
        return $this->sendResponse($user, '');
    }
    /**
     * Display the specified resource.
     */
    public function show($choice)
    {
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $choice =  $this->choiceRepo->find($lastParameter);
        return $this->sendResponse($choice, '');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Choice $choice)
    {
        $input = $request->all();
        $this->choiceRepo->update($input, $choice);
        return $this->sendSuccess('user updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Choice $choice)
    {
        $this->choiceRepo->delete($choice);
        return $this->sendSuccess('user deleted successfully');
    }
}
