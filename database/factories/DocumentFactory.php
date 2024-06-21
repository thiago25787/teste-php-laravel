<?php

namespace Database\Factories;

use App\Enums\CategoryEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => $this->faker->randomElement(CategoryEnum::toArray()),
            'financial_year' => $this->faker->year(),
            'title' => $this->faker->text(40),
            'contents' => $this->faker->realText(1400),
        ];
    }
}
