<?php

namespace App\Enums;

enum RolesEnum: string
{

    case SUPERADMIN = 'super_admin';
    case ADMIN = 'admin';
    case SUPERVISOR = 'supervisor';
    case MEMBER = 'member';

    // extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
    public function label(): string
    {
        return match ($this) {
            static::SUPERADMIN => 'Superadmins',
            static::ADMIN => 'Admins',
            static::SUPERVISOR => 'Supervisors',
            static::MEMBER => 'Members',
            
        };
    }
}
