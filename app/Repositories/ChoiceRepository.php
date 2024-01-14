<?php
namespace App\Repositories;
use App\Models\Choice;
use App\Models\Question;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
/**
 * Class ChoiceRepository
 */
class ChoiceRepository extends BaseRepository
{
    public $fieldSearchable = [
        'question_id',
    ];
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }
    public function model()
    {
        return Choice::class;
    }
    /**
     * @return mixed
     */
    public function store(array $input)
    {
        $choiceInputArray = Arr::only(
            $input,
            ['name', 'email', 'password']
        );
        $choiceInputArray["password"] =  Hash::make($choiceInputArray['password']);
        try {
            $user =  Choice::create($choiceInputArray);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    public function update($input,  $choice)
    {
        $choiceInputArray = Arr::only(
            $input,
            ['name']
        );
        try {
            DB::beginTransaction();
            $choice->update($choiceInputArray);
            DB::commit();
            return $choice;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
