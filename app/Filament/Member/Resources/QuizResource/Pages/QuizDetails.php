<?php

namespace App\Filament\Member\Resources\QuizResource\Pages;

use App\Filament\Member\Resources\QuizResource;
use App\Models\Quiz;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class QuizDetails extends Page
{
    protected static string $resource = QuizResource::class;

    protected static string $view = 'filament.member.resources.quiz-resource.pages.quiz-details';


    use InteractsWithRecord;
    public function getTitle(): string | Htmlable
    {
        $title = 'Quiz Details ';
        $title =  $this->record->isExpired() ? $title . '(Expired)' : $title;
        return  $title;
    }


    public function mount(int | string $record): void
    {

        $quiz = Quiz::where('slug', $record)->first();
        $this->record = $this->resolveRecord($quiz->id);
    }
}
