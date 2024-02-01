<?php
namespace App\Filament\Company\Resources\MemberQuizResource\Pages;
use App\Filament\Company\Resources\MemberQuizResource;
use App\Models\MemberQuiz;
use App\Models\Quiz;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
class ListMemberQuizzes extends ListRecords
{
    protected static string $resource = MemberQuizResource::class;
    protected function getHeaderActions(): array
    {
        return [
        ];
    }
    public function getTabs(): array
    {
        return [
            'All' => Tab::make()
                ->badge(MemberQuiz::count())
                ->icon('heroicon-m-user-group'),
            'Out of time' => Tab::make()
                ->icon('heroicon-m-user-group')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('quiz', function ($query) {
                    $query->where('test_type', 'out_of_time');
                }))->badge(MemberQuiz::whereHas('quiz', function ($query) {
                    $query->where('test_type', 'out_of_time');
                })->count()),
            'In time' => Tab::make()
                ->icon('heroicon-m-user-group')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('quiz', function ($query) {
                    $query->where('test_type', 'in_time');
                }))
                ->badge(MemberQuiz::whereHas('quiz', function ($query) {
                    $query->where('test_type', 'in_time');
                })->count()),
        ];
    }
}
