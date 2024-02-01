<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Enums\RolesEnum;
use App\Filament\Admin\Resources\UserResource;
use App\Models\Tenant;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use function Filament\Support\is_app_url;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $data['password'] = Hash::make($data['password']);
        $tenant = Tenant::create(['id' => $data['tenant_id']]);
        $tenant->domains()->create(['domain' => $data['tenant_id'] . '.quizzes.test']);
        $record = new ($this->getModel())($data);
        $record->save();
        $record->assignRole(RolesEnum::ADMIN);
        return $record;
    }
    public function create(bool $another = false): void
    {
        $this->authorizeAccess();
        try {
            $this->callHook('beforeValidate');
            $data = $this->form->getState();
            $this->callHook('afterValidate');
            $tenant = Tenant::find($data['tenant_id']);
            if ($tenant) {
                Notification::make()
                    ->title("tenant ID already used.")
                    ->icon('heroicon-o-document-text')
                    ->iconColor('danger')
                    ->send();
                throw new Halt;
            }
            $data = $this->mutateFormDataBeforeCreate($data);
            $this->callHook('beforeCreate');
            $this->record = $this->handleRecordCreation($data);
            $this->form->model($this->getRecord())->saveRelationships();
            $this->callHook('afterCreate');
        } catch (Halt $exception) {
            return;
        }
        $this->getCreatedNotification()?->send();
        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->getRecord()::class);
            $this->record = null;
            $this->fillForm();
            return;
        }
        $redirectUrl = $this->getRedirectUrl();
        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
    }
}
