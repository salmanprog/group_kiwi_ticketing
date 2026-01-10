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
        Schema::create('company_products', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('company_product_category_id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('image_url')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('status', ['1', '0'])->default('1');
            $table->string('unit');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_products');
    }
};
