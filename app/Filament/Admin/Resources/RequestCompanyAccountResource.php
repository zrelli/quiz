<?php
namespace App\Filament\Admin\Resources;
use App\Enums\RolesEnum;
use App\Filament\Admin\Resources\RequestCompanyAccountResource\Pages;
use App\Models\RequestCompanyAccount;
use App\Models\Tenant;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
class RequestCompanyAccountResource extends Resource
{
    protected static ?string $model = RequestCompanyAccount::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
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
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('subdomain'),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->sortable()->since()->label('Sent since'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('Accept')
                    ->color('success')
                    ->action(function (
                        RequestCompanyAccount $row
                    ) {
                        $tenant = Tenant::create(['id' => $row->subdomain]);
                        $tenant->domains()->create(['domain' => $row->subdomain . '.quizzes.test']);
                        $data = $row->toArray();
                        unset($data['subdomain']);
                        unset($data['id']);
                        $data['tenant_id'] = $row->subdomain;
                        $record = User::create($data);
                        $record->assignRole(RolesEnum::ADMIN);
                        $row->delete();
                        // TODOX: send email to user
                        // dispatch(new SendExamResultMailEvent(null, $this->record, $quizUrl));
                    })->icon('heroicon-m-building-office-2')->button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    BulkAction::make('Accept all')
                        ->color('success')
                        ->action(
                            function (Collection $records) {
                                $filteredUsers = [];
                                $filteredTenants = [];
                                $records->each(function ($record) use (&$filteredUsers, &$filteredTenants) {
                                    $filteredUsers[] = [
                                        'email' => $record->email,
                                        'name' => $record->name,
                                        'password' => $record->password,
                                        'tenant_id' => $record->subdomain,
                                    ];
                                    $filteredTenants[] = [
                                        'id' => $record->subdomain,
                                    ];
                                });
                                Tenant::insert(array_map(function ($tenant) {
                                    return ['id' => $tenant['id']];
                                }, $filteredTenants));
                                foreach ($filteredTenants as $tenant) {
                                    Tenant::find($tenant['id'])->domains()->create(['domain' => $tenant['id'] . '.quizzes.test']);
                                }
                                User::insert(array_map(function ($user) {
                                    return [
                                        'email' => $user['email'],
                                        'name' => $user['name'],
                                        'password' => $user['password'],
                                        'tenant_id' => $user['tenant_id'],
                                    ];
                                }, $filteredUsers));
                                $usersWithEmails = User::whereIn('email', array_column($filteredUsers, 'email'))->get();
                                foreach ($usersWithEmails as $user) {
                                    $user->assignRole(RolesEnum::ADMIN);
                                }
                                $records->each->delete();
                                // TODOX: send email to user
                                // dispatch(new SendExamResultMailEvent(null, $this->record, $quizUrl));
                                return redirect()->route('filament.admin.resources.request-company-accounts.index');
                            }
                        ),
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
            'index' => Pages\ListRequestCompanyAccounts::route('/'),
        ];
    }
}
