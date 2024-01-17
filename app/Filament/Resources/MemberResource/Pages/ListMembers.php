<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Exports\MembersExport;
use App\Filament\Resources\MemberResource;
use App\Models\Member;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;
    protected function getHeaderActions(): array
    {
        $exportTime = now()->toDateString();
        return [
            Actions\CreateAction::make(),
            Action::make('Export Data')
                ->color('success')
                ->label('Export Data')
                ->icon('heroicon-m-document')
                ->badge(
                    Member::count()
                )->action(fn () => (new MembersExport)->store("members-data-".$exportTime.".csv"))
        ];
    }
}
