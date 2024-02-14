<?php

namespace App\Filament\Member\Pages;

use App\Filament\Member\Widgets\DashboardStatisticWidget;
use App\Filament\Member\Widgets\MemberStatisticWidget;
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
            // AccountWidget::class,
            // FilamentInfoWidget::class,
            // DashboardStatisticWidget::class,
            MemberStatisticWidget::class,
        ];
    }
}



// protected function getHeaderWidgets(): array
// {
//     $widgets = [];
//     $quiz = $this->record;
//     // $isExpired =  isExpired(Carbon::parse($quiz->expired_at));
//     // if ($isExpired) {
//         ExamStatisticWidget::$id = $quiz->id;
//         $widgets[] = ExamStatisticWidget::class;
//    // }
//     return $widgets;
// }
