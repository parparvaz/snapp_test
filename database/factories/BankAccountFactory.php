<?php

namespace Database\Factories;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Random\RandomException;

class BankAccountFactory extends Factory
{
    protected $model = BankAccount::class;

    /**
     * @throws RandomException
     */
    public function definition(): array
    {
        return [
            'bank_name' => fake()->word(),
            'bank_account_number' => random_int(1000000000, 10000000000000),
            'iban_code' => fake()->numerify('IR#############################'),
            'balance' => random_int(100000, 10000000),
            'user_id' => User::factory(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    public function user(User|int|null $user = null): self
    {
        return $this->state(function () use ($user) {
            $user = $user ?? User::factory()->create();

            $user = $user instanceof User ? $user->getAttribute('id') : $user;

            return [
                'user_id' => $user,
            ];
        });
    }

    public function balance(string|int|null $balance = null): self
    {
        return $this->state(function () use ($balance) {
            $balance ??= random_int(100000, 10000000);

            return [
                'balance' => $balance
            ];
        });
    }
}
