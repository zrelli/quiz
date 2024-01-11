<?php

namespace Database\Seeders;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Role::findOrCreate(RolesEnum::SUPERADMIN->value);
      Role::findOrCreate(RolesEnum::ADMIN->value);
      
     // just for testing purposes

   

       Role::findOrCreate(RolesEnum::SUPERVISOR->value);
       Role::findOrCreate(RolesEnum::MEMBER->value);

       $role = Role::whereName(RolesEnum::ADMIN)->first();
       $permission = Permission::whereName(PermissionsEnum::MANAGEQUIZZES)->first();
       $role->givePermissionTo($permission);




    }
   
}
