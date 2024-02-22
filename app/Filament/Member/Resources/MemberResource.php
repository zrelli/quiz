<?php

namespace App\Filament\Member\Resources;

use App\Filament\Member\Resources\MemberResource\Pages;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Get;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;
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
                Forms\Components\TextInput::make('password')->password()
                    ->rules(['required', 'min:8'])->visibleOn('create'),
                Forms\Components\TextInput::make('confirm_password')->password()->same('password')
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
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('total_attempts'),
                Tables\Columns\TextColumn::make('score')->placeholder(0)->sortable(),
                Tables\Columns\TextColumn::make('created_at')->sortable()->since()->label('Registered since'),

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
            'index' => Pages\ListMembers::route('/'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
            'view' => Pages\ViewMember::route('/{record}/view'),
        ];
    }
}
