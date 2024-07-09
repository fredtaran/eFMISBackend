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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', total: 10, places: 2);
            $table->string('budget_no')->nullable();
            $table->timestamp('bno_timestamp')->nullable();
            $table->string('pr_no')->nullable();
            $table->timestamp('pr_timestamp')->nullable();
            $table->string('po_no')->nullable();
            $table->timestamp('po_timestamp')->nullable();
            $table->boolean('iar')->default(false);
            $table->timestamp('iar_timestamp')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
