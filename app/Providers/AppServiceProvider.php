<?php

namespace App\Providers;

use App\Packages\Rule\BankCardNumberRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private array $validatorsMap = [
        'ir_bank_card_number' => BankCardNumberRule::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ($this->validatorsMap as $name => $class) {
            Validator::extend($name, $class);
        }
    }
}
