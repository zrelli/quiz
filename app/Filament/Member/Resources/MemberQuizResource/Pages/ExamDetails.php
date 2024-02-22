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
        return '';
    }
    public function mount(int | string $record): void
    {
        $quiz = Quiz::where('slug', $record)->first();
        $this->record = $this->resolveRecord($quiz->id);
    }
}
