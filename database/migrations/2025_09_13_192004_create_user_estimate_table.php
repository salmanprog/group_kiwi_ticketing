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
        Schema::create('user_estimate', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('estimate_number')->unique();

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('company')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('contract_id')->nullable()->constrained('contracts')->onDelete('cascade');

            $table->date('issue_date')->nullable();
            $table->date('valid_until')->nullable();
            $table->date('event_date')->nullable();
            $table->text('note')->nullable();
            $table->text('terms')->nullable();
            $table->enum('status', ['draft', 'sent', 'approved','revised', 'rejected'])->default('draft');
            $table->enum('is_adjusted', ['1', '0'])->default('0');
            $table->boolean('is_open')->default(false);
            $table->decimal('subtotal', 12, 2)->default(0.00); 
            $table->decimal('total', 12, 2)->default(0.00);
            $table->decimal('tax_total', 12, 2)->default(0.00);
            $table->decimal('discount_total', 12, 2)->default(0.00);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_estimate');
    }
};
