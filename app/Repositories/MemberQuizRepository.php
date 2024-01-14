<?php
namespace App\Repositories;

use App\Models\MemberQuiz;
use App\Models\Question;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
/**
 * Class MemberQuizRepository
 */
class MemberQuizRepository extends BaseRepository
{
    public $fieldSearchable = [
        'quiz_id'
    ];
    
    
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
    
    public function model()
    {
        return MemberQuiz::class;
    }
    /**
     * @return mixed
     */
    public function store(array $input)
    {
        $memberQuizInputArray = Arr::only(
            $input,
            ['name', 'email', 'password']
        );
        $memberQuizInputArray["password"] =  Hash::make($memberQuizInputArray['password']);
        try {
            $memberQuiz =  MemberQuiz::create($memberQuizInputArray);
            DB::commit();
            return $memberQuiz;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    public function update($input,  $memberQuiz)
    {
        $memberQuizInputArray = Arr::only(
            $input,
            ['name']
        );
        try {
            DB::beginTransaction();
            $memberQuiz->update($memberQuizInputArray);
            DB::commit();
            return $memberQuiz;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
