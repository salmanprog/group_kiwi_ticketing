<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('invoice_id')->constrained('user_invoices')->onDelete('cascade');
            $table->foreignId('estimate_id')->constrained('user_estimate')->onDelete('cascade');
            $table->foreignId('contract_id')->constrained('contracts')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('reason')->nullable();
            $table->enum('status', ['open', 'settled'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_notes');
    }
};
