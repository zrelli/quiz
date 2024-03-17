<?php

namespace App\Filament\Company\Resources\QuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ChoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'choices';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->rules(['required', 'min:10', "max:500", 'string']),
                Forms\Components\Textarea::make('explanation')
                    ->rules(['required', 'min:10', "max:500", 'string']),
                Toggle::make('is_correct')
                    ->onColor('success')
                    ->offColor('danger'),
                Forms\Components\Hidden::make('question_id')->default(
                    $this->ownerRecord->id
                )
            ])->columns(1);
    }
    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query) => $query
                    ->orderByDesc('created_at')
            )
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')->limit(30),
                Tables\Columns\TextColumn::make('explanation')->limit(30),
                Tables\Columns\IconColumn::make('is_correct')->boolean(),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
