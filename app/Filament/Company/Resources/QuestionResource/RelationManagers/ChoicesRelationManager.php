<?php

namespace App\Filament\Company\Resources\QuestionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
                    ->offColor('danger')
            ])->columns(1);
    }
    public function table(Table $table): Table
    {
        return $table
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
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, string $model): Model {
                        if (!$this->ownerRecord->has_multiple_answers && $data['is_correct']) {
                            $this->ownerRecord->choices()
                                ->update(['is_correct' => false]);
                        }
                        $data['question_id'] = $this->ownerRecord->id;
                        return $model::create($data);
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make()->using(function (Model $record, array $data): Model {
                    if (!$this->ownerRecord->has_multiple_answers && $data['is_correct']) {
                        $this->ownerRecord->choices()
                            ->where('id', '!=', $record->id)
                            ->update(['is_correct' => false]);
                    }
                    $record->update($data);
                    return $record;
                }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
