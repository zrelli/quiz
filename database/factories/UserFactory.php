<?php

namespace Database\Factories;

use App\Enums\PermissionsEnum;
use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if($user->id ==2){
                //set role to admin
                $user->assignRole(RolesEnum::ADMIN);
                $permission = Permission::whereName(PermissionsEnum::MANAGEQUIZZES)->first();
                //set direct permission to admin
                $user->givePermissionTo($permission);


            }
           


        });
    }
}
