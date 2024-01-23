<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use App\Models\User;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run()
    {
        User::all()->each(function (User $user) {
            BankAccount::factory()->count(random_int(3,5))->user($user)->create();
        });
    }
}
