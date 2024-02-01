<?php

namespace App\Filament\Company\Resources\MemberResource\Pages;

use App\Filament\Company\Resources\MemberResource;
use App\Filament\Company\Widgets\MemberStatisticWidget;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [MemberStatisticWidget::class];
    }
}
