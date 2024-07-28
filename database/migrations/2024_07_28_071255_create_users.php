<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->autoIncrement()->unsigned();
            $table->string('username', 50)->nullable(true)->default(null);
            $table->string('password', 255)->nullable(true)->default(null);
            //$table->timestamps();
            //$table->softDeletes();
            $table->dateTime('created_at')->nullable(true)->default(null);
            $table->dateTime('updated_at')->nullable(true)->default(null);
            $table->dateTime('deleted_at')->nullable(true)->default(null);
        });
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};