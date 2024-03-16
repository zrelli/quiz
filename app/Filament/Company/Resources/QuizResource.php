<?php

namespace App\Filament\Company\Resources;

use App\Filament\Company\Resources\QuestionResource\RelationManagers\QuestionRelationManager;
use App\Filament\Company\Resources\QuizResource\Pages;
use App\Filament\Company\Resources\QuizResource\RelationManagers\ExamsRelationManager;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?int $navigationSort = 2;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        $options = [
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
        ];
        $durations = [
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
        ];
        $isCreatePage = request()->routeIs('filament.company.resources.quizzes.create');
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->rules(["required", "string", "min:10", "max:255"])
                    ->unique(ignoreRecord: true)
                    ->autofocus(),
                Forms\Components\Textarea::make('description')
                    ->rules(['min:10', "max:500", 'string']),
                Forms\Components\Section::make('')->schema([
                    Forms\Components\Select::make('test_type')
                        ->options([
                            'in_time' => "In time",
                            'out_of_time' => "Out of time",
                        ])
                        ->default('out_of_time')
                        ->live()
                        ->required(),
                        // ->disabledOn('edit'),
                    Forms\Components\DateTimePicker::make('started_at')
                        ->required($isCreatePage)
                        // ->disabledOn('edit')
                        ->default(now())
                        ->minDate($isCreatePage ?? now()),
                    Forms\Components\Select::make('max_attempts')
                        ->rules(['integer', 'min:1', 'max:4'])
                        ->options($options)->default('1'),
                ])->columns(3),
                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\Select::make('duration')
                            ->rules(['integer', 'min:1', 'max:7'])
                            // ->disabledOn('edit')
                            ->options($durations)
                            ->label(fn (Get $get): string =>  $get('test_type') == 'in_time' ?'Exam Duration (Hour)' : 'Expiration duration (Day)')

                            ->default('1'),
                        Forms\Components\Toggle::make('is_published')

                        ->hiddenOn('create')
                        // ->visibleOn('view')

                            ->default(false),
                        Forms\Components\Toggle::make('is_public')
                            ->default(true),
                    ])->columns(3),
            ])->columns(1);
    }
    public static function table(Table $table): Table
    {
        return $table

        ->modifyQueryUsing(fn (Builder $query) => $query
        ->orderByDesc('created_at')
        )

            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('test_type')->label('Type')
                    ->icon('heroicon-m-clock')
                    ->color(fn (string $state): string => $state == 'in_time' ? "danger" : "success")
                    ->formatStateUsing(fn (string $state): string => $state == 'in_time' ? "In time" : "Out of time"),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(30),
                Tables\Columns\TextColumn::make('expired_at')->since()->label('Expiration')->sortable(),
                Tables\Columns\TextColumn::make('questions_count')->counts('questions')->label('Questions'),
                Tables\Columns\TextColumn::make('exams_count')->counts('exams')->label('Exams'),
                Tables\Columns\ToggleColumn::make('is_published')
                ->afterStateUpdated(function ($record, $state) {
                    if ($state == $record->is_published && $state == true) {


                        $quiz = Quiz::with('questions.choices')->find($record->id);

                        $hasQuestionWithTwoChoices = $quiz->questions->contains(function ($question) {
                            return $question->choices->count() >= 2;
                        });

                        if (!$hasQuestionWithTwoChoices) {
                            Notification::make()->title("Quiz should has at least one question with choices!")
                                ->icon('heroicon-o-exclamation-triangle')
                                ->iconColor('danger')
                                ->send();
                                $record->is_published = false;
                                $record->save();

                        }
                    }
                })

                ,
                Tables\Columns\ToggleColumn::make('is_public'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([]);
    }
    public static function getRelations(): array
    {
        return  [
            QuestionRelationManager::class,
            ExamsRelationManager::class
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
            'view' => Pages\ViewQuiz::route('/{record}/view'),
        ];
    }
}
