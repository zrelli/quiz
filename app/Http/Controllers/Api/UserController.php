<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
class UserController extends AppBaseController
{
    use ResponseTrait, ApiRequestValidationTrait;
    /**
     * @var UserRepository
     */
    public $userRepo;
    /**
     * UserController constructor.
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userRepo->paginate(USERS_PER_PAGE);
        $users = UserResource::collection($users);
        return $this->sendResponse($users);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreUserRequest::class);
        $user =  $this->userRepo->store($input);
        $user = new UserResource($user);
        return $this->sendResponse($user);
    }
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user = new UserResource($user);
        return $this->sendResponse($user);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $input = $this->processRequest($request, UpdateUserRequest::class);
        $this->userRepo->update($input, $user);
        return $this->sendSuccess('user updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userRepo->delete($user);
        return $this->sendSuccess('user deleted successfully');
    }
}
