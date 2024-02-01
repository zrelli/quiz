<?php

namespace App\Filament\Company\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class MemberStatisticWidget extends BaseWidget
{
    public   static $id;
    public ?Model $record = null;
    protected function getStats(): array
    {
        return [
            Stat::make('Score', $this->record->score ?? 0)
                ->description('Average score')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Exams', $this->record->exams->count() ?? 0)
                ->description('Total Exams')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total time taken', $this->record->time_taken ?? 0)
                ->description('Total  time taken')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Attempts', $this->record->total_attempts ?? 0)
                ->description('Total Attempts')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total successful Attempts', $this->record->successful_attempts ?? 0)
                ->description('Total successful Attempts')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total failed Attempts', $this->record->failed_attempts ?? 0)
                ->description('Total failed Attempts')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
        ];
    }
}
