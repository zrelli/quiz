<?php
namespace App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class CreateQuiz extends CreateRecord
{
    protected static string $resource = QuizResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tenant_id'] = tenantId();
        $data['slug'] = Str::slug($data['title']);
        return $data;
    }
    protected function handleRecordCreation(array $data): Model
    {
        $datesData = [...$data];
        unset($data['validity_duration']);
        unset($data['test_duration']);
        unset($data['started_at']);
        $createdQuiz = static::getModel()::create($data);
        if (array_key_exists('test_duration', $datesData)) {
            $createdQuiz->setStartDateAndDuration($datesData['started_at'], $datesData['test_duration']);
            $createdQuiz->calculateExpirationDate();
        } else {
            $createdQuiz->calculateExpirationDate($datesData['validity_duration']);
        }
        return $createdQuiz;
    }
}
