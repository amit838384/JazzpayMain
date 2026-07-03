<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cafeteria_id');
            $table->integer('cafeteria_user_id')->nullable();
            $table->string('name');
            $table->integer('duration_days');
            $table->decimal('price', 10, 2);
            $table->string('meals')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('auto_renew')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_plans');
    }
};
