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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();

            $table->foreign('division_id')->references('id')->on('divisions')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('section_id')->references('id')->on('sections')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['division_id', 'section_id']);
            $table->dropColumn(['division_id', 'section_id']);
        });
    }
};
