<?php
namespace App\Enums;
enum PermissionsEnum: string
{
    case MANAGEQUIZZES = 'manage_quizzes';
    // extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
    public function label(): string
    {
        return match ($this) {
            static::MANAGEQUIZZES => 'Manage Quizzes',
        };
    }
}
