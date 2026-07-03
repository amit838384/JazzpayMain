<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_dish', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cafeteria_id')->nullable();
            $table->integer('dish_category_id')->nullable();
            $table->integer('ingredients_id')->nullable();
            $table->string('dish_name')->nullable();
            $table->text('description')->nullable();
            $table->string('price')->nullable();
            $table->string('serving_of')->nullable();
            $table->string('calories')->nullable();
            $table->string('protein')->nullable();
            $table->string('carbohydrates')->nullable();
            $table->string('fats')->nullable();
            $table->string('image')->nullable();
            $table->string('food_type', 100)->nullable();
            $table->integer('status')->default(1);
            $table->integer('view')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_dish');
    }
};
