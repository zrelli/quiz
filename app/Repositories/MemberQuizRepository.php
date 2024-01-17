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
            ['member_id']
        );
        try {
            if (!empty([$this->relationQuery])) {
                $memberQuizInputArray = [...$this->relationQuery, ...$memberQuizInputArray];
            }
            $memberQuiz =  MemberQuiz::create($memberQuizInputArray);
            DB::commit();
            return $memberQuiz;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    // public function update($input,  $memberQuiz)
    // {
    //     $memberQuizInputArray = Arr::only(
    //         $input,
    //         ['name']
    //     );
    //     try {
    //         DB::beginTransaction();
    //         $memberQuiz->update($memberQuizInputArray);
    //         DB::commit();
    //         return $memberQuiz;
    //     } catch (\Exception $e) {
    //         throw new UnprocessableEntityHttpException($e->getMessage());
    //     }
    // }
    //repeated many times should converted to trait or laravel action
    public function isAlreadyAssigned($memberId, $quizId)
    {
        $memberQuiz =   MemberQuiz::where(['member_id' => $memberId, 'quiz_id' => $quizId])->first();
        return $memberQuiz;
    }
}
