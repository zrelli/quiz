<?php

namespace App\Filament\Member\Resources\MemberResource\Pages;

use App\Exports\MembersExport;
use App\Filament\Member\Resources\MemberResource;
use App\Filament\Exports\MemberExporter;
use App\Models\Member;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Jobs\PrepareCsvExport;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;
    protected function getHeaderActions(): array
    {
        initTenant();
        // $totalMembers = Member::count();
        return [
            // ExportAction::make()
            //     ->label('Filament export members')
            //     ->chunkSize(100)
            //     ->job(PrepareCsvExport::class)
            //     ->badge($totalMembers)
            //     ->color('info')
            //     ->exporter(MemberExporter::class)
            //     ->formats([ExportFormat::Xlsx, ExportFormat::Csv,])
            //     ->icon('heroicon-m-document-text'),
            // Action::make('Export Data')
            //     ->color('success')
            //     ->label('Export Data')
            //     ->icon('heroicon-m-document')
            //     ->badge($totalMembers)
            //     ->action(
            //         function () {
            //             $exportTime = now()->toDateString();
            //             (new MembersExport)->queue("members-data-" . $exportTime . ".csv");
            //             Notification::make()->title("members data has been exported")
            //                 ->icon('heroicon-o-document-text')
            //                 ->iconColor('success')
            //                 ->send();
            //         }
            //     ),
            //     Actions\CreateAction::make(),

        ];
    }
}
