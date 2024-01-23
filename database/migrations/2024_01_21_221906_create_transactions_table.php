<?php

use App\Models\Transaction;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount');
            $table->bigInteger('fee')->default(500);
            $table->foreignId('source_id')->constrained('card_numbers');
            $table->foreignId('destination_id')->constrained('card_numbers');
            $table->string('status')->default(Transaction::PENDING);
            $table->string('uuid');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
