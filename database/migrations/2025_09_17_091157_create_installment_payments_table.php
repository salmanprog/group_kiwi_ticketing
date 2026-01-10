<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('estimate_id')->constrained('user_estimate')->onDelete('cascade');
            $table->integer('installment_number');
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->boolean('is_paid')->default(false);
            $table->dateTime('paid_at')->nullable();
            $table->enum('status', ['unpaid', 'paid', 'failed', 'cancelled'])->default('unpaid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_payments');
    }
};
