<?php
namespace Database\Factories;

use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    protected static $index = 1;
    protected static ?string $MemberPassword = "mp123456";
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'score' => fake()->numberBetween(1, 100),
            // 'tenant_id' => Tenant::inRandomOrder()->first()->id,
            // 'user_id' => User::inRandomOrder()->first()->id,
            // 'created_at' => now(),
            // 'updated_at' => now()
        ];
    }


    public function configure()
    {
        return $this->afterCreating(function (Member $member) {
            // $this->assignRoleToUser($user);
            self::$index++;
        });
    }
    /**
     * Indicate that the question belongs to a specific quiz.
     *
     * @param  int  $quizId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forMember( $tenantId): MemberFactory
    {
        $memberName = "member" . self::$index;
        $memberEmail = $memberName . "@msaaq.com";
        return $this->state(['name' => $memberName, "email" => $memberEmail, 'password' => Hash::make(static::$MemberPassword),'tenant_id' => $tenantId]);
        // return $this->state(['tenant_id' => $tenantId]);
    }
}
