<?php

namespace App\Filament\Admin\Resources\RequestCompanyAccountResource\Pages;

use App\Filament\Admin\Resources\RequestCompanyAccountResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequestCompanyAccount extends EditRecord
{
    protected static string $resource = RequestCompanyAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
