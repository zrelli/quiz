<?php

namespace App\Filament\Company\Resources\QuizResource\Pages;

use App\Filament\Company\Resources\QuizResource;
use App\Filament\Company\Widgets\ExamStatisticWidget;
use App\Filament\Company\Widgets\MemberStatisticWidget;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQuiz extends ViewRecord
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        $widgets = [];
        $quiz = $this->record;
        // $isExpired =  isExpired(Carbon::parse($quiz->expired_at));
        // if ($isExpired) {
            ExamStatisticWidget::$id = $quiz->id;
            $widgets[] = ExamStatisticWidget::class;
       // }
        return $widgets;
    }

}
