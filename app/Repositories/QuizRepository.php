<?php
namespace App\Repositories;
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
                // 'is_published',
                // 'validity_duration',
                'started_at',
                //
                'tenant_id'
            ]
        );
        try {
            $timeDuration = $quizInputArray['test_type'] == 'out_of_time'
                ? ($quizInputArray['duration'] * 24)
                : $quizInputArray['duration'];
            $quizInputArray['expired_at'] =  Carbon::createFromFormat('Y-m-d H:i:s', $quizInputArray['started_at'])
                ->addHours($timeDuration)->format('Y-m-d H:i:s');
            // unset($quizInputArray['validity_duration']);
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
                'is_published'
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
    public function toggleQuizPublishing($quiz)
    {
        $newPublishStatus = !$quiz->is_published;
        $quiz->is_published = $newPublishStatus;
        $quiz->save();
    }
    public function subscribeToQuiz($quiz, $memberId = null)
    {
        $memberId = $memberId ? $memberId : auth()->user()->id;
        try {
            DB::beginTransaction();
            $quiz->exams()->create(['quiz_id' => $quiz->id, 'member_id' => $memberId]);
            DB::commit();
            return $quiz;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
