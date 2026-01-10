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
        Schema::create('organization_event_type', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->enum('status', ['1', '0'])->default('1');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_event_type');
    }
};
