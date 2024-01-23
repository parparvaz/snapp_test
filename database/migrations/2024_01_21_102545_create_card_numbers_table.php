<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('card_numbers', function (Blueprint $table) {
            $table->id();
            $table->string("card_number");
            $table->foreignId("bank_account_id")->constrained('bank_accounts');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('card_numbers');
    }
};
