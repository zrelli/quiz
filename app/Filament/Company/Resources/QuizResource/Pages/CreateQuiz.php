<?php

namespace App\Filament\Company\Resources\QuizResource\Pages;

use App\Filament\Company\Resources\QuizResource;
use App\Repositories\QuizRepository;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateQuiz extends CreateRecord
{
    protected static string $resource = QuizResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        initTenant();
        return $data;
    }
    protected function handleRecordCreation(array $data): Model
    {
        $quizRepo = new QuizRepository(app());
        return  $quizRepo->store($data);
    }
}
