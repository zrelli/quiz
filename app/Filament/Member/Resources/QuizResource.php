<?php

namespace App\Filament\Member\Resources;

use App\Filament\Member\Resources\QuizResource\Pages;
use App\Filament\Member\Resources\QuizResource\RelationManagers\ExamsRelationManager;
use App\Models\Quiz;
use Filament\Forms\Form;
use Filament\Resources\Resource;
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
        return $form
            ->schema([])->columns(1);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->where('is_public', true)
                ->where('is_published', false))
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
            ])
            ->filters([
                //
            ])
            ->actions([

            ])
            ->bulkActions([]);
    }
    public static function getRelations(): array
    {
        return  [
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizzes::route('/'),
            'view' => Pages\QuizDetails::route('/{record:slug}/details'),
        ];
    }
}
