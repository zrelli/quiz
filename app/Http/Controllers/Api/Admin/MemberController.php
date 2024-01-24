<?php
namespace App\Http\Controllers\Api\Admin;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use App\Repositories\MemberRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\ResponseTrait;
class MemberController extends AppBaseController
{
    use ResponseTrait, ApiRequestValidationTrait;
    /**
     * @var MemberRepository
     */
    public $memberRepo;
    /**
     * MemberController constructor.
     */
    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepo = $memberRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = $this->memberRepo->paginate(MEMBER_PER_PAGE);
        return  MemberResource::collection($members);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $this->processRequest($request, StoreMemberRequest::class);
        $user =  $this->memberRepo->store($input);
        return new MemberResource($user);
    }
    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return new MemberResource($member);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $input = $this->processRequest($request, UpdateMemberRequest::class);
        $this->memberRepo->update($input, $member);
        return $this->sendSuccess('member updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $this->memberRepo->delete($member);
        return $this->sendSuccess('member deleted successfully');
    }
}
