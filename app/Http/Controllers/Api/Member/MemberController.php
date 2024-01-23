<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\MemberResource;
use App\Models\Member;
use App\Repositories\MemberRepository;
use App\Traits\ApiRequestValidationTrait;
use Illuminate\Http\ResponseTrait;

class MemberController extends AppBaseController
{
    use ResponseTrait, ApiRequestValidationTrait;
    /**
     * @var MemberRepository
     */
    public $memberRepo;
    /**
     * UserController constructor.
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
        return MemberResource::collection($members);
    }
    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return new MemberResource($member);
    }
}
