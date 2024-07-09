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
        Schema::create('allocations', function (Blueprint $table) {
            $table->id();
            $table->string('program');
            $table->string('code');
            $table->string('amount');
            $table->string('year');
            $table->unsignedBigInteger('line_id')->nullable();
            $table->unsignedBigInteger('fs_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->timestamps();

            $table->foreign('line_id')->references('id')->on('line_items')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('fs_id')->references('id')->on('fund_sources')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('section_id')->references('id')->on('sections')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocations');
    }
};
