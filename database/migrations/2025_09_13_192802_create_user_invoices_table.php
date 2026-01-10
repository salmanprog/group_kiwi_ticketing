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
        Schema::create('user_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('invoice_number')->unique();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('company')->onDelete('cascade');
            $table->foreignId('estimate_id')->constrained('user_estimate')->onDelete('cascade');
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('set null');
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->text('note')->nullable();
            $table->text('terms')->nullable();
            $table->enum('status', ['unpaid', 'partial', 'paid', 'cancelled'])->default('unpaid');
            $table->decimal('subtotal', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2)->default(0.00);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->boolean('is_installment')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_invoices');
    }
};
