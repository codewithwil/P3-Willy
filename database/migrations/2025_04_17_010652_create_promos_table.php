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
        Schema::create('promos', function (Blueprint $table) {
            $table->id('promoId');
            $table->unsignedBigInteger('branch_id')->nullable(true);
            $table->string('promoCode', 20);
            $table->string('promoName', 75);
            $table->text('description')->nullable(true);
            $table->date('startDate');
            $table->date('endDate');
            $table->tinyInteger('typePromo');
            $table->tinyInteger('target_audience')->default(1)->comment('1 => "member", 2 => "branch');
            $table->decimal('amountPromo', 12, 2);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('branch_id')
            ->references('branchId')
            ->on('branches')
            ->onUpdate("cascade")
            ->onDelete("restrict");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
