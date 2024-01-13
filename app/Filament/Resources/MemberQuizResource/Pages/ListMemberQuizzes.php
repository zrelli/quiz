<?php

namespace App\Filament\Resources\MemberQuizResource\Pages;

use App\Filament\Resources\MemberQuizResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberQuizzes extends ListRecords
{
    protected static string $resource = MemberQuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
