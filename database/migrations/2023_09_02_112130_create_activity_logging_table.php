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
        Schema::create('activity_logging', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('ip_address');
            $table->string('user_agent');
            $table->string('http_method');
            $table->string('route_name');
            $table->string('url');
            $table->text('request_payload')->nullable();
            $table->text('request_header')->nullable();
            $table->integer('http_status_code');
            $table->longText('response')->nullable();
            $table->string('content_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logging');
    }
};
