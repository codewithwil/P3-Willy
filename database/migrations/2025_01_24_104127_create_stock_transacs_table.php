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
        if(!Schema::hasTable('stock_transacs')) {
            Schema::create('stock_transacs', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('stockTransacId');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('commodity_id');
                $table->tinyInteger('type')->comment('1: In; 2: Out');
                $table->unsignedInteger('quantity');
                $table->text('desc');
                $table->timestamps();

                $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate("cascade")
                ->onDelete("restrict");

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
        Schema::dropIfExists('stock_transacs');
    }
};
