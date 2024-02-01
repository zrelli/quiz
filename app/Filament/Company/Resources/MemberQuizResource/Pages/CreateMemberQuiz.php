<?php
namespace App\Filament\Company\Resources\MemberQuizResource\Pages;
use App\Filament\Company\Resources\MemberQuizResource;
use App\Models\MemberQuiz;
use App\Models\Quiz;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
class CreateMemberQuiz extends CreateRecord
{
    protected static string $resource = MemberQuizResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        $quizId = $data['quiz_id'];
        $memberIds = $data['member_id'];
        $memberQuizData = collect($memberIds)->map(function ($memberId) use ($quizId) {
            return ['member_id' => intval($memberId), 'quiz_id' => intval($quizId)];
        });
        $quiz = Quiz::find($data['quiz_id']);
        try {
            DB::beginTransaction();
            DB::table('member_quizzes')->insert($memberQuizData->toArray());
            DB::commit();
            return $quiz;
        } catch (\Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
    protected function getRedirectUrl(): string
    {
        $resource = static::getResource();
        return $resource::getUrl('index');
    }
}
