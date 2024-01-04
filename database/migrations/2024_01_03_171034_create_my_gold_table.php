<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('my_gold', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->float('buy_price', 8, 2);
            $table->float('value', 8, 2);
            $table->float('weight', 8, 4);
            $table->float('target_sell_price', 8, 2)->nullable();
            $table->float('target_baht_profit', 8, 2)->nullable();
            $table->boolean('sold')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_gold');
    }
};
