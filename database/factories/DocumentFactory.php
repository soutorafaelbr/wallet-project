<?php

namespace Database\Factories;

use Domain\Document\Enum\DocumentTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'document_type' => DocumentTypeEnum::CPF,
            'number' => $this->faker->unique()->numerify('###########'),
        ];
    }

    public function cnpj(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'document_type' => DocumentTypeEnum::CNPJ,
            'number' => $this->faker->unique()->numerify('##############'),
        ]);
    }
}
