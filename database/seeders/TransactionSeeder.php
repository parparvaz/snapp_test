<?php

namespace Database\Seeders;

use App\Models\CardNumber;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Random\RandomException;

class TransactionSeeder extends Seeder
{
    /**
     * @throws RandomException
     */
    public function run(): void
    {
        $sources = CardNumber::with([
            'bankAccount' => fn($q) => $q->with('user')
        ])->get();

        foreach ($sources as $source) {
            $destinations = CardNumber::whereHas(
                'bankAccount', fn($query) => $query->whereHas(
                'user', fn($q) => $q->whereNot('id', $source->bankAccount->user->getAttribute('id'))
            )
            )->inRandomOrder()->take(random_int(5, 10))->get();

            foreach ($destinations as $destination) {
                Transaction::factory()->count(random_int(1, 3))->source($source)->destination($destination)->create();

            }
        }
    }
}
