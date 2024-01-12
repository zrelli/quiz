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
    protected static ?string $SuperAdminPassword = "sap123456";
    protected static ?string $AdminPassword = "ap123456";
    protected static ?string $SupervisorPassword = "sp123456";
    protected static ?string $MemberPassword = "mp123456";
    protected static $index = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            // 'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            // 'password' => static::$password ??= Hash::make('password'),
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
            $this->assignRoleToUser($user);
            self::$index++;
        });
    }
    /**
     * Admin Custom Fields
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forSuperAdmin(): UserFactory
    {
        return $this->state(['email' => "superadmin@masaq.com", 'name' => 'superadmin', 'password' => Hash::make(static::$SuperAdminPassword)]);
    }
    /**
     * Admin Custom Fields
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forAdmin(): UserFactory
    {
        $adminName = "admin" . self::$index;
        $adminEmail = $adminName . "@masaq.com";
        return $this->state(['name' => $adminName, "email" => $adminEmail, 'password' => Hash::make(static::$AdminPassword)]);
    }
    /**
     * Admin Custom Fields
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forSupervisor(): UserFactory
    {
        $supervisorName = "supervisor" . self::$index;
        $supervisorEmail = $supervisorName . "@masaq.com";
        return $this->state(['name' => $supervisorName, "email" => $supervisorEmail, 'password' => Hash::make(static::$SupervisorPassword)]);
    }
    /**
     * Admin Custom Fields
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forMember(): UserFactory
    {
        $memberName = "member" . self::$index;
        $memberEmail = $memberName . "@masaq.com";
        return $this->state(['name' => $memberName, "email" => $memberEmail, 'password' => Hash::make(static::$SupervisorPassword)]);
    }
    public static function resetIndex()
    {
        self::$index = 1;
    }
    //for development purposes
    private function assignRoleToUser($user)
    {
        $email = $user->email;
        if (Str::contains($email, 'superadmin')) {
            // $user->assignRole(RolesEnum::ADMIN);
            $role = RolesEnum::SUPERADMIN;
        } else if (Str::contains($email, 'admin')) {
            $role = RolesEnum::ADMIN;
        } else if (Str::contains($email, 'supervisor')) {
            $role = RolesEnum::SUPERVISOR;
        } else {
            $role = RolesEnum::MEMBER;
        }
        $user->assignRole($role);
    }
    //todo 
    private function assignDirectPermissionToUser($user)
    {
        $permission = Permission::whereName(PermissionsEnum::MANAGEQUIZZES)->first();
        //set direct permission to admin
        $user->givePermissionTo($permission);
    }
}
