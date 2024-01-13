<?php
namespace App\Filament\Resources;
use App\Filament\Resources\MemberQuizResource\Pages;
use App\Filament\Resources\MemberQuizResource\RelationManagers;
use App\Models\MemberQuiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
class MemberQuizResource extends Resource
{
    protected static ?string $model = MemberQuiz::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('member.user.name'),
                Tables\Columns\IconColumn::make('is_successful')->boolean()->placeholder('empty')
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
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMemberQuizzes::route('/'),
            'create' => Pages\CreateMemberQuiz::route('/create'),
            'edit' => Pages\EditMemberQuiz::route('/{record}/edit'),
        ];
    }
}
