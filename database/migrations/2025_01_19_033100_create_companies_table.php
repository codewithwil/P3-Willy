<?php

use Illuminate\{
    Database\Migrations\Migration,
    Database\Schema\Blueprint,
    Support\Facades\Schema,
};

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table){
                $table->engine = "InnoDB";
                $table->id('companyId');   
                $table->string('image')->nullable(true);   
                $table->string('name', 50);   
                $table->string('email')->unique();   
                $table->string('phone', 16);   
                $table->text('address');   
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
