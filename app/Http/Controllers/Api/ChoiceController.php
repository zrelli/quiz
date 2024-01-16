<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreChoiceRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateChoiceRequest;
use App\Http\Resources\ChoiceResource;
use App\Models\Choice;
use App\Repositories\ChoiceRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
        $choices = $this->choiceRepo->paginate(CHOICES_PER_PAGE);
        $choices = ChoiceResource::collection($choices);
        return $this->sendResponse($choices);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreChoiceRequest::class);
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $lastParameter ?? throw new ModelNotFoundException();
        $this->choiceRepo->setRelationQuery(['question_id' => $lastParameter]);
        $choice =  $this->choiceRepo->store($input);
        $choice = new ChoiceResource($choice);
        return $this->sendResponse($choice);
    }
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $choice =  $this->choiceRepo->find($lastParameter);
        $choice ?? throw new ModelNotFoundException();
        $choice = new ChoiceResource($choice);
        return $this->sendResponse($choice);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $input = $this->processRequest($request, UpdateChoiceRequest::class);
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $question =  $this->choiceRepo->find($lastParameter);
        $question ?? throw new ModelNotFoundException();
        $this->choiceRepo->update($input, $question);
        return $this->sendSuccess('choice updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $parameters = func_get_args();
        $lastParameter = end($parameters);
        $choice =  $this->choiceRepo->find($lastParameter);
        $choice ?? throw new ModelNotFoundException();
        $this->choiceRepo->delete($choice);        return $this->sendSuccess('choice deleted successfully');
    }
}
