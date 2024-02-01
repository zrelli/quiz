<?php

namespace App\Filament\Company\Resources\QuestionResource\Pages;

use App\Filament\Company\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;
}
