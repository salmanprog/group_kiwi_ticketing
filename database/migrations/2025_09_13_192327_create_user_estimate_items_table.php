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
        Schema::create('user_estimate_items', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('user_estimate_id')->constrained('user_estimates')->onDelete('cascade');
            $table->string('name', 255);
            $table->integer('quantity');
            $table->string('unit', 255);
            $table->decimal('price', 10, 2);
            $table->decimal('total_price', 12, 2);
            $table->decimal('product_price', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('gratuity', 10, 2);
            $table->timestamps(); 
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_estimate_items');
    }
};
