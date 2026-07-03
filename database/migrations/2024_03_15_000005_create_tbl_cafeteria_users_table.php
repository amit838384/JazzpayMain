<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_cafeteria_users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cafeteria_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('role', 100)->nullable();
            $table->string('invite_code', 11)->nullable();
            $table->integer('status')->default(1);
            $table->integer('view')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_cafeteria_users');
    }
};
