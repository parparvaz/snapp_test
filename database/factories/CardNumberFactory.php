<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\CardNumber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CardNumberFactory extends Factory
{
    protected $model = CardNumber::class;

    public function definition(): array
    {
        return [
            'card_number' => fake()->numerify('################'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'bank_account_id' => BankAccount::factory()
        ];
    }

    public function bankAccount(BankAccount|null|int $bankAccount = null): self
    {
        return $this->state(function () use ($bankAccount) {
            $bankAccount ??= CardNumber::factory()->create();

            $bankAccount = $bankAccount instanceof CardNumber ? $bankAccount->getAttribute('id') : $bankAccount;
            return [
                'bank_account_id' => $bankAccount,
            ];
        });
    }

    public function cardNumber(?string $cardNumber = null): self
    {
        return $this->state(function () use ($cardNumber) {
            $cardNumber ??= fake()->numerify('################');

            return [
                'card_number' => $cardNumber,
            ];
        });
    }
}
