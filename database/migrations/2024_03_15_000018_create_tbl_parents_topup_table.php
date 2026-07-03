<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_parents_topup', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('parent_id')->nullable();
            $table->string('transaction_number', 100)->nullable();
            $table->integer('amount')->nullable();
            $table->integer('payment_status')->default(0)->comment('1-success, 2-failed, 0-pending');
            $table->integer('is_processed')->default(0)->comment('1-success, 0-pending');
            $table->integer('status')->default(1);
            $table->integer('view')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_parents_topup');
    }
};
