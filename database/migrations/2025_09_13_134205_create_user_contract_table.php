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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('contract_number')->unique();

            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('company')->onDelete('cascade');
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('cascade');

            $table->date('event_date')->nullable();
            $table->decimal('total', 12, 2)->default(0.00);
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            
            $table->enum('is_accept', ['accepted', 'rejected','pending'])->default('pending');
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
