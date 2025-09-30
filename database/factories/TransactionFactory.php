<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'payer_id' => fn () => User::factory(),
            'payee_id' => fn () => User::factory(),
            'amount' => 100.00,
        ];
    }
}
