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
        if(!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('supplierId');
                $table->string('name', 50);
                $table->string('email', 50)->unique();
                $table->unsignedBigInteger('phone');
                $table->text('address');
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
