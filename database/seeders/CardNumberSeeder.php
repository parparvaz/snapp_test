<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\CardNumber;
use Illuminate\Database\Seeder;

class CardNumberSeeder extends Seeder
{
    public function run()
    {
        BankAccount::all()->each(function (BankAccount $bankAccount) {
            CardNumber::factory()->bankAccount($bankAccount)->count(random_int(3, 5))->create();
        });
    }
}
