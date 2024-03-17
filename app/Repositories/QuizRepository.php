<?php

namespace App\Repositories;

use App\Events\SendExamInvitationMailsEvent;
use App\Events\SendExamResultMailEvent;
use App\Events\SendMemberExamReminderMailsEvent;
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
                'is_public',
                'started_at',
                'tenant_id'
            ]
        );
        try {
            $timeDuration = $quizInputArray['test_type'] == 'out_of_time'
                ? ($quizInputArray['duration'] * 24)
                : $quizInputArray['duration'];
            $quizInputArray['expired_at'] =  Carbon::createFromFormat('Y-m-d H:i:s', $quizInputArray['started_at'])
                ->addHours($timeDuration)->format('Y-m-d H:i:s');
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
                'max_attempts',
                'test_type',
                'duration',
                'is_public',
                'started_at'
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
    public function inviteMembers($quiz, $ids, $examInvitationUrl = null)
    {
        initTenant();
        $examInvitationUrl = $examInvitationUrl ?? route('members.exam-invitation', 'code_placeholder');
        dispatch(new SendExamInvitationMailsEvent($ids, $quiz, $examInvitationUrl));
    }
    public function remindMembers($quiz, $ids, $quizUrl = null)
    {
        initTenant();
        $slug = $quiz->slug;
        $quizUrl = $quizUrl ?? route('filament.member.resources.member-quizzes.view', $slug);
        dispatch(new SendMemberExamReminderMailsEvent($ids, $quiz, $quizUrl));
    }
    public function sendExamResults($quiz)
    {
        initTenant();
        $slug = $quiz->slug;
        $quizUrl = $quizUrl ?? route('filament.member.resources.member-quizzes.view', $slug);
        $quiz->is_answers_visible = true;
        $quiz->save();
        dispatch(new SendExamResultMailEvent(null, $quiz, $quizUrl));
    }
}
