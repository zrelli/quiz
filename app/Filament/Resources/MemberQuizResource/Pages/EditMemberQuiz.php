<?php

namespace App\Filament\Resources\MemberQuizResource\Pages;

use App\Filament\Resources\MemberQuizResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberQuiz extends EditRecord
{
    protected static string $resource = MemberQuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
