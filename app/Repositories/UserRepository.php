<?php
namespace App\Repositories;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
/**
 * Class UserRepository
 */
class UserRepository extends BaseRepository
{
    public $fieldSearchable = [
        'email',
    ];
    /**
     * {@inheritDoc}
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
    /**
     * {@inheritDoc}
     */
    public function model()
    {
        return User::class;
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
            $user =  User::create($userInputArray);
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
            ['name']
        );
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
