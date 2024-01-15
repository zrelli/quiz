<?php
namespace Database\Factories;
use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    protected static $index = 1;
    protected static $duration = 2;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence;
        $slug = Str::slug($title);
        $maxAttempts =  fake()->optional(0.7)->numberBetween(1, 1)
            ?? fake()->numberBetween(2, 3);
        $randomTotalDays =  fake()->numberBetween(1, 4);
        $expirationPeriod =    24 * $randomTotalDays;
        $expiredAt = Carbon::parse(now())->addHours($expirationPeriod);
        return [
            // 'tenant_id' => Tenant::inRandomOrder()->first()->id,
            'title' => $title,
            'slug' => $slug,
            'description' => fake()->paragraph,
            'max_attempts' => $maxAttempts,
            'expired_at' => $expiredAt
            // 'test_type' => fake()->randomElement(['in_time', 'out_of_time']),
            // 'is_out_of_time' => fake()->boolean,
            // 'is_expired' => fake()->boolean,
            // 'created_at' => now(),
            // 'updated_at' => now()
        ];
    }
    /**
     * Admin Custom Fields
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forOutOfTimeQuiz($tenantId): QuizFactory
    {
        // $memberName = "member" . self::$index;
        // $memberEmail = $memberName . "@msaaq.com";
        return $this->state(
            [
                'test_type' => 'out_of_time',
                // 'is_out_of_time' => true,
                'tenant_id' => $tenantId,
                // 'expired_at'=>$expiredAt,
            ]
        );
    }
    /**
     * Admin Custom Fields
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forInTimeQuiz($tenantId): QuizFactory
    {
        // $memberName = "member" . self::$index;
        // $memberEmail = $memberName . "@msaaq.com";
        return $this->state(
            [
                'test_type' => 'in_time',
                // 'is_out_of_time' => false,
                'tenant_id' => $tenantId,
                'expired_at' => now(), //fake
            ]
        );
    }
    public static function resetIndex()
    {
        self::$index = 1;
    }
    public function configure()
    {
        return $this->afterCreating(function (Quiz $quiz) {
            $starTime = now();
            if ($quiz->test_type == 'in_time') {
                $quiz->setStartDateAndDuration($starTime, self::$duration);
                $expiredAt = Carbon::parse($starTime)->addHours(self::$duration);
                $quiz->expired_at = $expiredAt;
                $quiz->save();
            }
        });
    }
    public static function setDuration($duration)
    {
        self::$duration = $duration;
    }
}
