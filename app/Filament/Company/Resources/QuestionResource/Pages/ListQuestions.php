<?php

namespace App\Filament\Company\Resources\QuestionResource\Pages;

use App\Filament\Company\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
