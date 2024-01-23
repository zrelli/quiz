<?php
namespace App\Repositories;
use App\Models\Member;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
/**
 * Class UserRepository
 */
class MemberRepository extends BaseRepository
{
    public $fieldSearchable = [
        'email',
    ];
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
    public function model()
    {
        return Member::class;
    }
    /**
     * @return mixed
     */
    public function store(array $input)
    {
        $userInputArray = Arr::only(
            $input,
            ['name', 'email', 'password']
        );
        $userInputArray["password"] =  Hash::make($userInputArray['password']);
        try {
            $user =  Member::create($userInputArray);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    public function update($input,  $user)
    {
        $userInputArray = Arr::only(
            $input,
            ['name', 'email', 'password']
        );
        if (array_key_exists('password', $userInputArray)) {
            $input['password'] = Hash::make($userInputArray['password']);
        }
        try {
            DB::beginTransaction();
            $user->update($userInputArray);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
