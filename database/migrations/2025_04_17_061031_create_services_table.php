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
        Schema::create('services', function (Blueprint $table) {
            $table->id('serviceId');
            $table->unsignedBigInteger('branch_id');
            $table->string('name', 75);
            $table->decimal('pricePerUnit', 12, 2);
            $table->string('unitType', 50);
            $table->integer('minQuantity');
            $table->text('description')->nullable(true);
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
        Schema::dropIfExists('services');
    }
};
