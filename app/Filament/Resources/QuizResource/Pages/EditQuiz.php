<?php
namespace App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
class EditQuiz extends EditRecord
{
    protected static string $resource = QuizResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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
        //todo
        $data = $this->getRecord()->attributesToArray();
        // $data['validity_duration'] = 1;
        // $datesData = [...$data];
        // unset($data['validity_duration']);
        // unset($data['test_duration']);
        // unset($data['started_at']);
        // $data['validity_duration']=1;
        // $data['test_duration']=1;
        // $data['started_at']=now();
        // // $createdQuiz = static::getModel()::create($data);
        // if ($data['test_type'] == 'in_time') {
        //     // $createdQuiz->setStartDateAndDuration($datesData['started_at'], $datesData['test_duration']);
        //     // $createdQuiz->calculateExpirationDate();
        //     $data['test_duration']=1;
        //     $data['started_at']=now();
        // } else {
        //     // $createdQuiz->calculateExpirationDate($datesData['validity_duration']);
        // }
        /** @internal Read the DocBlock above the following method. */
        $this->fillFormWithDataAndCallHooks($data);
    }
}
