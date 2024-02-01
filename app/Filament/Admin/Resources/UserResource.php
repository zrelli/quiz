<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 1;
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->rules(['required', 'string', 'max:255']),
                Forms\Components\TextInput::make('email')->email()
                    ->rules(['required', 'string', 'lowercase', 'email', 'max:255',])
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('tenant_id')->rules(['required', 'string', 'max:255'])->suffix('.quizzes.test'),
                Forms\Components\TextInput::make('password')->password()
                    ->rules(['required', 'min:8'])->visibleOn('create'),
                Forms\Components\Section::make('Update password')->schema([
                    Forms\Components\Toggle::make('update_password')->label('Show password form')->columnSpanFull()->live(),
                    Forms\Components\TextInput::make('new_password')->password()->rules(['required', 'min:8'])
                        ->visible(fn (Get $get): bool => $get('update_password')),
                    Forms\Components\TextInput::make('confirm_password')->password()->same('new_password')
                        ->rules(['required', 'min:8'])->visible(fn (Get $get): bool => $get('update_password')),
                ])->columns(2)->visibleOn('edit'),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('tenant_id'),
                Tables\Columns\TextColumn::make('created_at')->since()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
