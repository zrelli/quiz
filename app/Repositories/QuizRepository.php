<?php
namespace App\Repositories;
use App\Models\MemberQuiz;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
/**
 * Class QuizRepository
 */
class QuizRepository extends BaseRepository
{
    public $fieldSearchable = [
        'title',
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
            [
                'title',
                'description',
                'max_attempts',
                'test_type',
                'duration',
                'validity_duration',
                'started_at'
            ]
        );
        try {
            if ($quizInputArray['test_type'] == 'out_of_time') {
                $quizInputArray = $this->createOutOfTimeQuiz($quizInputArray);
            } else {
                $quizInputArray = $this->createInTimeQuiz($quizInputArray);
            }
            unset($quizInputArray['validity_duration']);
            $quiz =  Quiz::create($quizInputArray);
            DB::commit();
            return $quiz;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    public function update($input,  $quiz)
    {
        $quizInputArray = Arr::only(
            $input,
            [
                'title',
                'description',
            ]
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
    public function subscribeToQuiz(  $quiz,$memberId)
    {
        try {
            DB::beginTransaction();
            // $memberQuiz =
             $quiz->exams()->create(['quiz_id' => $quiz->id, 'member_id' => $memberId]);
            DB::commit();
            return $quiz;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    private function   createOutOfTimeQuiz(
        $quizInputArray
    ) {
        $quizInputArray['expired_at'] =  Carbon::createFromFormat('Y-m-d H:i:s', $quizInputArray['started_at'])->addDays($quizInputArray['validity_duration'])->format('Y-m-d H:i:s');
        return $quizInputArray;
    }
    private function   createInTimeQuiz($quizInputArray)
    {
        $quizInputArray['expired_at'] =  Carbon::createFromFormat('Y-m-d H:i:s', $quizInputArray['started_at'])->addHours($quizInputArray['duration'])->format('Y-m-d H:i:s');
        return $quizInputArray;
    }
     //repeated many times should converted to trait or laravel action
     public function isAlreadyAssigned($memberId, $quizId)
     {
         $memberQuiz =   MemberQuiz::where(['member_id' => $memberId, 'quiz_id' => $quizId])->first();
         return $memberQuiz;
     }
}
