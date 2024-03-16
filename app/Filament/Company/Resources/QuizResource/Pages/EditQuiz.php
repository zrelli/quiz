<?php

namespace App\Filament\Company\Resources\QuizResource\Pages;

use App\Events\SendExamInvitationMailsEvent;
use App\Events\SendExamResultMailEvent;
use App\Events\SendMemberExamReminderMailsEvent;
use App\Filament\Company\Resources\QuizResource;
use App\Models\Member;
use App\Models\Quiz;
use App\Repositories\QuizRepository;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Auth;
use function Filament\Support\is_app_url;

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
        $quizRepo = new QuizRepository(app());
        return [
            Action::make('Remind members')
                ->hidden($this->record->exams_count != 0 || $this->record->isExpired())
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
                ->action(function (array $data) use ($quizRepo) {
                    $quizRepo->remindMembers($this->record, $data['membersIds']);
                }),
            Action::make('Remind all members')
                ->action(function () use ($quizRepo) {
                    $quizRepo->remindMembers($this->record, null);
                })->icon('heroicon-m-envelope')->outlined()
                ->hidden($this->record->exams_count != 0 || $this->record->isExpired()),
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
                ->action(function (array $data) use ($quizRepo) {
                    $quizRepo->inviteMembers($this->record, $data['membersIds']);
                })
                ->hidden($this->record->isExpired()),
            Action::make('Invite all members')
                ->action(function () use ($quizRepo) {
                    $quizRepo->inviteMembers($this->record, null);
                })->icon('heroicon-m-user-plus')->outlined()
                ->hidden($this->record->isExpired()),
            Action::make('Send Results')
                ->color('success')
                ->action(function () use ($quizRepo) {
                    $this->record->is_answers_visible = true;
                    $this->record->save();
                    $quizRepo->sendExamResults($this->record);
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
    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();
        try {
            $this->callHook('beforeValidate');
            $data = $this->form->getState();
            $this->callHook('afterValidate');
            $canToggleQuizPublishingStatus =  Auth::user()->can('canToggleQuizPublishingStatus', $this->record);
            $publishStatusChanged =  $data['is_published'] != $this->record->is_published && $data['is_published'] == true;
            if (!$canToggleQuizPublishingStatus && $publishStatusChanged) {
                Notification::make()->title("Quiz should has at least one question with choices!")
                    ->icon('heroicon-o-exclamation-triangle')
                    ->iconColor('danger')
                    ->send();
                throw new Halt;
            }
            $data = $this->mutateFormDataBeforeSave($data);
            $this->callHook('beforeSave');
            $this->handleRecordUpdate($this->getRecord(), $data);
            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }
        $this->getSavedNotification()?->send();
        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }
}
