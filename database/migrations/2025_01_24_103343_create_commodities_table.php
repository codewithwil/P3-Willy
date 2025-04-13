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
        if(!Schema::hasTable('commodities')) {
            Schema::create('commodities', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('commoditiesId');
                $table->unsignedBigInteger('room_Id');
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('unit_id');
                $table->string('image', 200);
                $table->string('name', 50);
                $table->string('merk', 50)->nullable(true);
                $table->text('desc')->nullable(true);
                $table->unsignedBigInteger('price')->default(0); 
                $table->tinyInteger('type')->comment('1: Alat, 2: Sparepart');
                $table->tinyInteger('status')->default(1);
                $table->timestamps();

                
                $table->foreign('room_Id')
                ->references('roomId')
                ->on('rooms')
                ->onUpdate("cascade")
                ->onDelete("restrict");

                $table->foreign('category_id')
                ->references('categoryId')
                ->on('categories')
                ->onUpdate("cascade")
                ->onDelete("restrict");

                $table->foreign('unit_id')
                ->references('unitId')
                ->on('units')
                ->onUpdate("cascade")
                ->onDelete("restrict");
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodities');
    }
};
