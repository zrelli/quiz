<?php
namespace App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource;
use App\Models\Tenant;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use function Filament\Support\is_app_url;
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($data['tenant_id'] != $record->tenant_id) {
            $tenant = Tenant::create(['id' => $data['tenant_id']]);
            $tenant->domains()->create(['domain' => $data['tenant_id'] . '.quizzes.test']);
        }
        $record->update($data);
        return $record;
    }
    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();
        try {
            $this->callHook('beforeValidate');
            $data = $this->form->getState();
            $this->callHook('afterValidate');
            $tenant = User::where('id', '!=', $this->record->id)->where('tenant_id', $data['tenant_id'])->first();
            if ($tenant) {
                Notification::make()
                    ->title("tenant ID already used.")
                    ->icon('heroicon-o-document-text')
                    ->iconColor('danger')
                    ->send();
                throw new Halt;
            }
            if ($data['update_password']) {
                if ($data['new_password'] != $data['confirm_password']) {
                    Notification::make()
                        ->title("members passwords don't match.")
                        ->icon('heroicon-o-document-text')
                        ->iconColor('danger')
                        ->send();
                    throw new Halt;
                }
                $data['password'] = Hash::make($data['new_password']);
                unset($data['new_password']);
                unset($data['confirm_password']);
            }
            unset($data['update_password']);
            //
            $data = $this->mutateFormDataBeforeSave($data);
            $this->callHook('beforeSave');
            $this->handleRecordUpdate($this->getRecord(), $data);
            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }
        $this->getSavedNotification()?->send();
        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }
}
