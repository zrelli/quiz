<?php

namespace App\Filament\Company\Resources\QuizResource\Pages;

use App\Events\SendExamInvitationMailsEvent;
use App\Events\SendExamResultMailEvent;
use App\Events\SendMemberExamReminderMailsEvent;
use App\Filament\Company\Resources\QuizResource;
use App\Models\Member;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\EditRecord;

class EditQuiz extends EditRecord
{
    protected static string $resource = QuizResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->authorizeAccess();
        $this->fillForm();
        $this->previousUrl = url()->previous();
    }
    protected function fillForm(): void
    {
        $data = $this->getRecord()->attributesToArray();
        $this->fillFormWithDataAndCallHooks($data);
    }
    protected function getFormActions(): array
    {
        initTenant();
        $slug = $this->record->slug;
        $quizUrl = route('filament.member.resources.member-quizzes.view', $slug);
        $examInvitationUrl = route('members.exam-invitation', 'code_placeholder');
        return [
            Action::make('Remind members')
                ->hidden($this->record->exams_count == 0 || $this->record->isExpired())
                ->form([
                    Section::make('')->schema([
                        Select::make('membersIds')
                            ->label('Members')
                            ->searchable()
                            ->getSearchResultsUsing(
                                function (string $search): array {
                                    return  $this->remindMembersOptions($search);
                                }
                            )
                            ->multiple()
                            ->required(),
                    ])->columns(1),
                ])->outlined()->icon('heroicon-m-envelope')
                ->action(function (array $data) use ($quizUrl) {
                    dispatch(new SendMemberExamReminderMailsEvent($data['membersIds'], $this->record, $quizUrl));
                }),
            Action::make('Remind all members')
                ->action(function () use ($quizUrl) {
                    dispatch(new SendMemberExamReminderMailsEvent(null, $this->record, $quizUrl));
                })->icon('heroicon-m-envelope')->outlined()
                ->hidden($this->record->exams_count == 0 || $this->record->isExpired()),
            Action::make('Invite members')
                ->form([
                    Section::make('')->schema([
                        Select::make('membersIds')
                            ->label('Members')
                            ->searchable()
                            ->getSearchResultsUsing(
                                function (string $search): array {
                                    return $this->inviteMembersOptions($search);
                                }
                            )
                            ->multiple()
                            ->required(),
                    ])->columns(1),
                ])->outlined()->icon('heroicon-m-user-plus')
                ->action(function (array $data) use ($examInvitationUrl) {
                    dispatch(new SendExamInvitationMailsEvent($data['membersIds'], $this->record, $examInvitationUrl));
                })

                ->hidden($this->record->isExpired()),
            Action::make('Invite all members')
                ->action(function () use ($examInvitationUrl) {
                    dispatch(new SendExamInvitationMailsEvent(null, $this->record, $examInvitationUrl));
                })->icon('heroicon-m-user-plus')->outlined()
                ->hidden($this->record->isExpired()),
            Action::make('Send Results')
                ->color('success')
                ->action(function () use ($quizUrl) {
                    $this->record->is_answers_visible = true;
                    $this->record->save();
                    dispatch(new SendExamResultMailEvent(null, $this->record, $quizUrl));
                })->icon('heroicon-m-megaphone')->outlined()
                ->visible($this->record->exams_count > 0 && $this->record->isExpired()),
            ...parent::getFormActions(),
        ];
    }
    private function remindMembersOptions($search): array
    {
        return   Member::query()
            ->limit(50)
            ->where('email', 'like', "%{$search}%")
            ->whereHas('exams', function ($query) {
                $currentQuizId = $this->record->id;
                $query->where('quiz_id', $currentQuizId);
            })
            ->pluck('email', 'id')
            ->toArray();
    }
    private function inviteMembersOptions($search): array
    {
        return Member::query()
            ->limit(50)
            ->where('email', 'like', "%{$search}%")
            ->whereDoesntHave('invitations', function ($query) {
                $currentQuizId = $this->record->id;
                $query->where('quiz_id', $currentQuizId);
            })
            ->pluck('email', 'id')
            ->toArray();
    }
}
