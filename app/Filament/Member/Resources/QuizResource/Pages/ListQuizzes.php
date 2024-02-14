<?php

namespace App\Filament\Member\Resources\QuizResource\Pages;

use App\Filament\Member\Resources\QuizResource;
use App\Filament\Imports\QuizImporter;
use App\Models\Quiz;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;

class ListQuizzes extends ListRecords
{
    protected static string $resource = QuizResource::class;
    public function getTabs(): array
    {
        return [
            'All' => Tab::make()
                ->badge(Quiz::count())->icon('heroicon-m-user-group'),
            'Out of time' => Tab::make()
                ->badge(Quiz::where('test_type', 'out_of_time')->count())->icon('heroicon-m-user-group')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('test_type', 'out_of_time')),
            'In time' => Tab::make()
                ->badge(Quiz::where('test_type', 'in_time')->count())->icon('heroicon-m-user-group')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('test_type', 'in_time')),
        ];
    }
}
