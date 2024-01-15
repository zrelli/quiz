<?php
namespace App\Repositories;
use App\Models\Question;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
/**
 * Class QuestionRepository
 */
class QuestionRepository extends BaseRepository
{
    public $fieldSearchable = [
        'quiz_id',
    ];
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
    public function model()
    {
        return Question::class;
    }
    /**
     * @return mixed
     */
    public function store(array $input)
    {
        $questionInputArray = Arr::only(
            $input,
            ['question', 'description']
        );
        try {
            if(!empty([$this->relationQuery])){
                $questionInputArray = [...$this->relationQuery,...$questionInputArray];
            }
            $user =  Question::create($questionInputArray);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    public function update($input,  $question)
    {
        $questionInputArray = Arr::only(
            $input,
            ['question', 'description']
        );
        try {
            DB::beginTransaction();
            $question->update($questionInputArray);
            DB::commit();
            return $question;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
