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
            $table->id(); // bigint(20) UNSIGNED AUTO_INCREMENT
            $table->integer('company_id');
            $table->integer('company_product_category_id');
            $table->string('slug')->index();
            $table->string('name');
            $table->string('description')->nullable();
            $table->text('image_url')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('status', ['1', '0'])->default('1');
            $table->string('unit');
            $table->timestamps(); // creates created_at and updated_at
            $table->softDeletes(); // creates deleted_at
            $table->decimal('tax', 10, 2)->nullable()->default(0.00);
            $table->decimal('gratuity', 10, 2)->nullable()->default(0.00);
            $table->decimal('total_price', 10, 2)->nullable()->default(0.00);
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
