<?php

namespace App\Filament\Member\Resources\MemberResource\Pages;

use App\Filament\Member\Resources\MemberResource;
use Filament\Resources\Pages\ListRecords;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;
    protected function getHeaderActions(): array
    {
        return [];
    }
}
