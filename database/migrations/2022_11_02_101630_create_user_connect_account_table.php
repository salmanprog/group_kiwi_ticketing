<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_connect_account', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->date('date_of_birth');
            $table->string('ssn',10);
            $table->text('id_front',500);
            $table->text('id_back',500);
            $table->string('city',50);
            $table->string('state',50);
            $table->string('street',50);
            $table->string('phone',50);
            $table->string('postal_code',50);
            $table->enum('status',['pending','verified','unverified'])->default('pending');
            $table->text('due_fields')->nullable();
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_connect_account');
    }
};
