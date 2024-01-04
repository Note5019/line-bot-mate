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
        Schema::create('gold', function (Blueprint $table) {
            $table->id();
            $table->string('gold_type');
            $table->string('gold_code');
            $table->float('buy', 8, 2);
            $table->float('sell', 8, 2);
            $table->float('buy_change', 8, 2);
            $table->float('sell_change', 8, 2);
            $table->timestamp('time_update');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gold');
    }
};
