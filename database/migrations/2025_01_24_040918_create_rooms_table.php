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
        if(!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('roomId');
                $table->unsignedBigInteger('building_Id');
                $table->string('roomName', 50);
                $table->unsignedInteger('floor');
                $table->tinyInteger('roomStatus')->default(1);
                $table->timestamps();

                $table->foreign('building_Id')
                ->references('buildingId')
                ->on('buildings')
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
        Schema::dropIfExists('rooms');
    }
};
