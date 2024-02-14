<?php
namespace App\Filament\Member\Resources\MemberResource\Pages;
use App\Filament\Member\Resources\MemberResource;
use App\Filament\Member\Widgets\MemberStatisticWidget;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
class ViewMember extends ViewRecord
{
    protected static string $resource = MemberResource::class;
    protected function getHeaderActions(): array
    {
        $headerActions = [];
        if (auth()->user()->id == $this->record->id) {
            $headerActions[] = Actions\EditAction::make();
        }
        return $headerActions;
    }
    protected function getHeaderWidgets(): array
    {
        return [MemberStatisticWidget::class];
    }
}
