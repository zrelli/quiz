<?php

namespace App\Filament\Company\Pages;

use App\Filament\Company\Widgets\DashboardStatisticWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return  [
            AccountWidget::class,
            FilamentInfoWidget::class,
            DashboardStatisticWidget::class,
        ];
    }
}
