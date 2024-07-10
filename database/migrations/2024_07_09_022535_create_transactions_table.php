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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator')->nullable();
            $table->boolean('is_pr')->nullable();
            $table->unsignedBigInteger('allocation_id')->nullable();
            $table->timestamp('date')->nullable();
            $table->string('obr_no')->nullable();
            $table->timestamp('obr_timestamp')->nullable();
            $table->decimal('obr_amount', total: 10, places: 2)->nullable();
            $table->string('obr_month')->nullable();
            $table->string('obr_year')->nullable();
            $table->string('creditor')->nullable();
            $table->string('dv_no')->nullable();
            $table->timestamp('dv_timestamp')->nullable();
            $table->decimal('dv_amount', total: 10, places: 2)->nullable();
            $table->string('dv_month')->nullable();
            $table->string('dv_year')->nullable();
            $table->decimal('obr_unpaid', total: 10, places: 2)->nullable();
            $table->string('ada_no')->nullable();
            $table->string('ada_timestamp')->nullable();
            $table->string('activity_title')->nullable();
            $table->string('saa_title')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('from')->nullable();
            $table->unsignedBigInteger('to')->nullable();
            $table->boolean('received')->default(false);
            $table->timestamps();

            $table->foreign('creator')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('allocation_id')->references('id')->on('allocations')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('from')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('to')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
