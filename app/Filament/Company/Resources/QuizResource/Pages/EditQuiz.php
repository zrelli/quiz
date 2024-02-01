<?php
namespace App\Filament\Company\Resources\QuizResource\Pages;
use App\Filament\Company\Resources\QuizResource;
use App\Jobs\MemberExamReminder;
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

        return [
            Action::make('Remind members')
                ->form([
                    Section::make('')->schema([
                        Select::make('membersIds')
                            ->label('Members')
                            ->searchable()
                            ->getSearchResultsUsing(
                                function (string $search): array {
                                    return Member::query()
                                        ->limit(50)
                                        ->where('email', 'like', "%{$search}%")
                                        ->whereHas('exams', function ($query) {
                                            $currentQuizId = $this->record->id;
                                            $query->where('quiz_id', $currentQuizId);
                                        })
                                        ->pluck('email', 'id')
                                        ->toArray();
                                }
                            )
                            ->optionsLimit(50)
                            ->multiple()
                            ->required(),
                    ])->columns(1),
                ])->outlined()->icon('heroicon-m-envelope')
                ->action(function (array $data) {
                    $membersIds =  $data['membersIds'];
                    $chunkSize = 100;
                    Member::select('id', 'name', 'email', 'tenant_id')
                        ->whereIn('id', $membersIds)
                        ->chunk($chunkSize, function ($members) {
                            dispatch(new MemberExamReminder($members));
                        });
                }),
            Action::make('Remind all members')
                ->action(function () {
                    $chunkSize = 100;
                    Member::select('id', 'name', 'email', 'tenant_id')
                        ->whereHas('exams', function ($query) {
                            $currentQuizId = $this->record->id;
                            $query->where('quiz_id', $currentQuizId);
                        })
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->chunk($chunkSize, function ($members) {
                            dispatch(new MemberExamReminder($members));
                        });
                })->icon('heroicon-m-envelope')->outlined(),
            ...parent::getFormActions(),
        ];
    }
}
