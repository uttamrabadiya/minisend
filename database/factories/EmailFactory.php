<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class EmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $tags = ['B2B', 'B2C', 'first-email', 'marketing', 'spam-filter', 'dns-check', 'random-word'];
        $mailText = fake()->realText(2000);
        return [
            'from' => fake()->unique()->safeEmail(),
            'reply_to' => fake()->unique()->safeEmail(),
            'subject' => fake()->text(65),
            'text' => $mailText,
            'html' => $mailText,
            'tags' => Arr::random($tags, 4),
            'status' => 1,
        ];
    }
}
