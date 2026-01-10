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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_type_id');
            $table->foreignId('event_type_id');
            $table->foreignId('event_history_id')->default(0);
            $table->foreignId('client_id')->default(0);
            $table->foreignId('company_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('department')->nullable();
            $table->text('address_one')->nullable();
            $table->text('address_two')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('size')->nullable();
            $table->text('first_meeting')->nullable();
            $table->string('hot_button')->nullable();
            $table->text('closing_probability')->nullable();
            $table->text('event_date')->nullable();
            $table->text('event_status')->nullable();
            $table->text('next_objective')->nullable();
            $table->text('follow_up_date')->nullable();
            $table->enum('status', ['1', '0'])->default('1');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
