<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\MemberQuizResource\Pages;
use App\Models\Member;
use App\Models\MemberQuiz;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;


class MemberQuizResource extends Resource
{
    protected static ?string $model = MemberQuiz::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'Exams';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('quiz_id')
                    ->label('Quizze')
                    ->options(fn (): Collection => Quiz::query()
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->pluck('title', 'id'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                Forms\Components\Select::make('member_id')
                    ->label('Members')
                    ->options(fn (Get $get): Collection => Member::query()
                        ->whereDoesntHave('exams', function ($query) use ($get) {
                            $query->where('quiz_id', $get('quiz_id'));
                        })
                        ->where('tenant_id', auth()->user()->tenant_id)
                        ->pluck('email', 'id'))
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->live()
                    ->required(),
            ])->columns(1);
    }
    public static function table(Table $table): Table
    {
        return $table

        ->modifyQueryUsing(fn (Builder $query) => $query
        ->orderByDesc('created_at')
        )            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('member.name')
                    ->url(fn (MemberQuiz $record): string => route('filament.company.resources.members.view', $record->member->id)),
                Tables\Columns\TextColumn::make('quiz.title')->label('Quiz Title')
                    ->url(fn (MemberQuiz $record): string => route('filament.company.resources.quizzes.view', $record->quiz->id)),
                Tables\Columns\TextColumn::make('quiz.test_type')->label('Quiz Type')->icon('heroicon-m-clock')
                    ->color(fn (string $state): string => $state == 'in_time' ? "danger" : "success")
                    ->formatStateUsing(fn (string $state): string => $state == 'in_time' ? "In time" : "Out of time"),
                Tables\Columns\IconColumn::make('is_successful')->boolean()->placeholder('empty'),
                Tables\Columns\TextColumn::make('exam_statistics_count')->counts('examStatistics'),
                Tables\Columns\TextColumn::make('created_at')->sortable()->since()->label('Started since'),
            ])
            ->filters([
                SelectFilter::make('Quizzes')
                    ->relationship('quiz', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Filter by Quiz')
                    ->indicator('Quiz'),
                SelectFilter::make('Members')
                    ->relationship('member', 'email')
                    ->searchable()
                    ->preload()
                    ->label('Filter by User')
                    ->indicator('User'),
            ])
            ->actions([])
            ->bulkActions([]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberQuizzes::route('/'),
        ];
    }
}
