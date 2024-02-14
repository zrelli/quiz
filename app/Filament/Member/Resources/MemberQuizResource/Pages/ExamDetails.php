<?php

namespace App\Filament\Member\Resources\MemberQuizResource\Pages;

use App\Filament\Member\Resources\QuizResource;
use App\Models\Quiz;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class ExamDetails extends Page
{
    protected static string $resource = QuizResource::class;

    protected static string $view = 'filament.member.resources.member-quiz-resource.pages.exam-details';


    use InteractsWithRecord;
    public function getTitle(): string | Htmlable
    {
        // $title = 'Exam starting page ';
        // $title =  $this->record->isExpired() ? $title . '(Expired)' : $title;
        // return  $title;
        return '';
    }


    public function mount(int | string $record): void
    {
        $slug = $record;
        $quiz = Quiz::where('slug', $slug)->first();
        $this->record = $this->resolveRecord($quiz->id);
    }
}
