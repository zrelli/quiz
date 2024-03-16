<?php
namespace App\Filament\Company\Resources;
use App\Filament\Company\Resources\QuestionResource\Pages;
use App\Filament\Company\Resources\QuestionResource\RelationManagers\ChoicesRelationManager;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('question') ->rules(['required','min:10', "max:500", 'string']),
                Forms\Components\Textarea::make('description') ->rules(['required','min:10', "max:500", 'string']),
            ])->columns(1);
    }
    public static function table(Table $table): Table
    {
        return $table

        ->modifyQueryUsing(fn (Builder $query) => $query
        ->orderByDesc('created_at')
        )
            ->columns([
                Tables\Columns\TextColumn::make('question'),
                Tables\Columns\TextColumn::make('description')->limit(30),
                //
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
            ChoicesRelationManager::class
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

}
