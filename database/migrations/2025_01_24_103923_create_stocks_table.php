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
        if(!Schema::hasTable('stocks')) {
            Schema::create('stocks', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('stockId');
                $table->unsignedBigInteger('commodity_id');
                $table->unsignedInteger('quantity');
                $table->timestamps();

                $table->foreign('commodity_id')
                ->references('commoditiesId')
                ->on('commodities')
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
        Schema::dropIfExists('stocks');
    }
};
