<?php
namespace App\Filament\Company\Resources\MemberResource\Pages;
use App\Filament\Company\Resources\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Hash;
use function Filament\Support\is_app_url;
class EditMember extends EditRecord
{
    protected static string $resource = MemberResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }
   
    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();
        try {
            $this->callHook('beforeValidate');
            $data = $this->form->getState();
            $this->callHook('afterValidate');
            if ($data['update_password']) {
                $data['password'] = Hash::make($data['new_password']);
                unset($data['new_password']);
                unset($data['confirm_password']);
            }
            unset($data['update_password']);
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
