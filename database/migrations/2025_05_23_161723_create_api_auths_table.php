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
        if (!Schema::hasTable('api_auths')) {
            Schema::create('api_auths', function (Blueprint $table) {
                $table->id();
                $table->string('url');
                $table->text('password');
                $table->string('user_name');
                $table->string('token')->nullable();
                $table->string('identifier');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_auths');
    }
};
