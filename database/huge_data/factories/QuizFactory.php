<?php
namespace Database\Factories;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence;
        $slug = Str::slug($title);
        return [
            'tenant_id' => Tenant::inRandomOrder()->first()->id,
            'title' => $title,
            'slug' => $slug,
            'description' => fake()->paragraph,
            'max_attempts' => fake()->randomDigitNotNull,
            'test_type' => fake()->randomElement(['in_time', 'out_of_time']),
            // 'is_out_of_time' => fake()->boolean,
            'is_expired' => fake()->boolean,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
