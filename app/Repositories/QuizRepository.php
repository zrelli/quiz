<?php
namespace App\Repositories;
use App\Models\Quiz;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
/**
 * Class QuizRepository
 */
class QuizRepository extends BaseRepository
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
        return Quiz::class;
    }
    /**
     * @return mixed
     */
    public function store(array $input)
    {
        $quizInputArray = Arr::only(
            $input,
            ['name', 'email', 'password']
        );
        $quizInputArray["password"] =  Hash::make($quizInputArray['password']);
        try {
            $user =  Quiz::create($quizInputArray);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    public function update($input,  $quiz)
    {
        $quizInputArray = Arr::only(
            $input,
            ['name']
        );
        try {
            DB::beginTransaction();
            $quiz->update($quizInputArray);
            DB::commit();
            return $quiz;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
