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
        if(!Schema::hasTable('branches')) {
            Schema::create('branches', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('branchId');
                $table->string('branchName', 75);
                $table->text('address');
                $table->string('email', 75)->unique();
                $table->string('operationalHours', 50);    
                $table->bigInteger('phone');    
                $table->decimal('ltd', 65, 30);    
                $table->decimal('lng', 65, 30);    
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
        Schema::dropIfExists('branches');
    }
};
