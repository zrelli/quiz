<?php

namespace App\Filament\Company\Resources\MemberResource\Pages;

use App\Filament\Company\Resources\MemberResource;
use App\Models\Member;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;
    protected function handleRecordCreation(array $data): Model
    {
        initTenant();
        unset($data['confirm_password']);
        $data['password'] = Hash::make($data['password']);
        $member = Member::create($data);
        return $member;
    }
}
