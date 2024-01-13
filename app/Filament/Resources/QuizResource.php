<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\RelationManagers\QuestionRelationManager;
use App\Filament\Resources\QuizResource\Pages;
use App\Filament\Resources\QuizResource\RelationManagers;
use App\Models\Quiz;
use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->autofocus(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\Select::make('max_attempts')
                    ->options([
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 3,
                    ])->default('1'),
                Forms\Components\Select::make('test_type')
                    ->options([
                        'in_time' => "In time",
                        'out_of_time' => "Out of time",
                    ])
                    ->default('out_of_time')
                    ->live()
                    ->afterStateUpdated(function (?string $state, ?string $old) {
                        // dd($state);
                        // ...
                    }),
                // Forms\Components\DateTimePicker::make('expired_at')
                //     // ->seconds(false)
                //     ->minDate(now()->addHour()),
                Forms\Components\DateTimePicker::make('started_at')
                    // ->seconds(false)
                    ->minDate(now())->hidden(fn (Get $get): bool =>  $get('test_type') != 'in_time'),
                Forms\Components\Select::make('validity_duration')
                    ->label('Validity Duration (days)')
                    ->options([
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                    ])
                    ->default('1')
                    ->hidden(fn (Get $get): bool =>  $get('test_type') == 'in_time'),
                Forms\Components\Select::make('test_duration')
                    ->label('Test Duration (hours)')
                    ->options([
                        '1' => 1,
                        '2' => 2,
                        '3' => 3,
                        '4' => 4,
                    ])->hidden(fn (Get $get): bool =>  $get('test_type') != 'in_time'),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')->limit(30),
                Tables\Columns\TextColumn::make('expired_at')->since()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            QuestionRelationManager::class
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'create' => Pages\CreateQuiz::route('/create'),
            'edit' => Pages\EditQuiz::route('/{record}/edit'),
        ];
    }
}
// ->requiredWith('name'),
