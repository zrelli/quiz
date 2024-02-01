<?php

namespace App\Filament\Company\Resources\QuestionResource\RelationManagers;

use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class QuestionRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';
    public function form(Form $form): Form
    {
        return $form
            ->schema([


                Forms\Components\Textarea::make('question')
                    ->rules(['required', 'min:10', "max:500", 'string']),
                Forms\Components\Textarea::make('description')
                    ->rules(['required', 'min:10', "max:500", 'string']),
            ])->columns(1);
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question')
            ->columns([
                Tables\Columns\TextColumn::make('question')->limit(40),
                Tables\Columns\TextColumn::make('description')->limit(30),
                Tables\Columns\TextColumn::make('choices_count')->counts('choices'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()
                    ->url(fn (Question $record): string => route('filament.company.resources.questions.edit', $record))
                // ->openUrlInNewTab()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->questions->count();
    }
}
