<?php
namespace App\Filament\Company\Widgets;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class ExamStatisticWidget extends BaseWidget
{
    public   static $id;
    public ?Model $record = null;
    protected function getStats(): array
    {
        $statistics = DB::table('member_quizzes')
            ->selectRaw('COUNT(*) as total_exams')
            ->where("quiz_id",$this->record ?  $this->record->id:null)
            ->whereNotNull('is_successful')
            ->selectRaw('AVG(score) as score')
            ->selectRaw('AVG(time_taken) as time_taken')
            ->selectRaw('COUNT(total_attempts) as total_attempts')
            ->selectRaw('SUM(CASE WHEN is_successful = true THEN 1 ELSE 0 END) as successful_attempts')
            ->selectRaw('SUM(CASE WHEN is_successful = false THEN 1 ELSE 0 END) as failed_attempts')
            ->first();
        return [
            Stat::make('Exams', $statistics->total_exams)
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
