<?php

namespace App\Filament\Company\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class DashboardStatisticWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $statistics = DB::table('members')
            ->selectRaw('COUNT(*) as total_members')
            ->whereNotNull('score')
            ->whereNotNull('time_taken')
            ->whereNotNull('total_attempts')
            ->whereNotNull('successful_attempts')
            ->whereNotNull('failed_attempts')
            ->selectRaw('AVG(score) as score')
            ->selectRaw('AVG(time_taken) as time_taken')
            ->selectRaw('COUNT(total_attempts) as total_attempts')
            ->selectRaw('COUNT(successful_attempts) as successful_attempts')
            ->selectRaw('COUNT(failed_attempts) as failed_attempts')
            ->first();
        return [
            Stat::make('Members', $statistics->total_members)
                ->description('All members from the database')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Average score', $statistics->score ?? 0)
                ->description('Average members score')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('success'),
            Stat::make('Total time taken', $statistics->time_taken ?? 0)
                ->description('Total members time taken')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('success'),
            Stat::make('Total Attempts', $statistics->total_attempts)
                ->description('Total members attempts')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total successful Attempts', $statistics->successful_attempts)
                ->description('Total members successful attempts')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total failed Attempts', $statistics->failed_attempts)
                ->description('Total members failed attempts')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('danger'),
        ];
    }
}
