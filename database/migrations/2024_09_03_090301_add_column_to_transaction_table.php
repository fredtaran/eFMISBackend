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
        Schema::table('transactions', function (Blueprint $table) {
            $table->decimal('dv_gross', total: 10, places: 2)->nullable();
            $table->decimal('dv_tax', total: 10, places: 2)->nullable();
            $table->decimal('dv_retention', total: 10, places: 2)->nullable();
            $table->decimal('dv_penalty', total: 10, places: 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['dv_gross', 'dv_tax', 'dv_retention', 'dv_penalty']);
        });
    }
};
