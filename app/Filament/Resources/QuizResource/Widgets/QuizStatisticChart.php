<?php

namespace App\Filament\Resources\QuizResource\Widgets;

use Filament\Widgets\ChartWidget;

class QuizStatisticChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
