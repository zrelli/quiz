<?php
namespace App\Filament\Member\Pages;
use App\Filament\Member\Widgets\MemberStatisticWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return  [
            MemberStatisticWidget::class,
        ];
    }
}
