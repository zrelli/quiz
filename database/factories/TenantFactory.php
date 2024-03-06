<?php
namespace Database\Factories;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    protected static $index = 1;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
    public function configure()
    {
        return $this->afterCreating(function (Tenant $tenant) {
            $tenant->domains()->create(['domain' => $tenant->id . '.quizzes.test']);
            // set tenant_id to admins;
            $adminEmail = "admin" . self::$index . "@msaaq.com";
            $supervisorEmail = "supervisor" . self::$index . "@msaaq.com";
            $memberEmail = "member" . self::$index . "@msaaq.com";
            $admin = User::where('email', $adminEmail)->first();
            $admin->tenant_id = $tenant->id;
            $admin->save();
            $supervisor = User::where('email', $supervisorEmail)->first();
            $supervisor->tenant_id = $tenant->id;
            $supervisor->save();
            // $member = User::where('email', $memberEmail)->first();
            // $member->tenant_id = $tenant->id;
            // $member->save();
            self::$index++;
        });
    }
    /**
     * Indicate that the question belongs to a specific quiz.
     *
     * @param  int  $quizId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function setTenantIdFromIndex(): TenantFactory
    {
        $tenantIdentifier = 'tenant' . self::$index;
        return $this->state(['id' => $tenantIdentifier]);
    }
    public static function resetIndex()
    {
        self::$index = 1;
    }
}
