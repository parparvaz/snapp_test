<?php

namespace Database\Factories;

use App\Models\CardNumber;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'amount' => fake()->randomNumber(),
            'fee' => fake()->randomNumber(),
            'status' => fake()->randomElement(Transaction::STATUS),
            'uuid' => fake()->uuid(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'source_id' => CardNumber::factory(),
            'destination_id' => CardNumber::factory(),
        ];
    }

    public function source(CardNumber|null|int $cardNumber = null): self
    {
        return $this->state(function () use ($cardNumber) {
            $cardNumber ??= CardNumber::factory()->create();

            $cardNumber = $cardNumber instanceof CardNumber ? $cardNumber->getAttribute('id') : $cardNumber;
            return [
                'source_id' => $cardNumber,
            ];
        });
    }

    public function destination(CardNumber|null|int $cardNumber = null): self
    {
        return $this->state(function () use ($cardNumber) {
            $cardNumber ??= CardNumber::factory()->create();

            $cardNumber = $cardNumber instanceof CardNumber ? $cardNumber->getAttribute('id') : $cardNumber;
            return [
                'destination_id' => $cardNumber,
            ];
        });
    }
}
