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
        Schema::create('organization_users', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('slug');
            $table->integer('organization_id');
            $table->integer('client_id');
            $table->integer('created_by');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mobile_no');
            $table->string('position');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_users');
    }
};
