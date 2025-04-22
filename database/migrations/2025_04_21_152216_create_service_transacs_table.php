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
        Schema::create('service_transacs', function (Blueprint $table) {
            $table->id('serviceTransId');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('weight', 6, 2);
            $table->text('note');
            $table->tinyInteger('paymentMethod');
            $table->tinyInteger('deliverOption');
            $table->decimal('postage', 12, 2);
            $table->decimal('total', 12, 2);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->foreign('branch_id')
            ->references('branchId')
            ->on('branches')
            ->onUpdate("cascade")
            ->onDelete("restrict");

            $table->foreign('service_id')
            ->references('serviceId')
            ->on('services')
            ->onUpdate("cascade")
            ->onDelete("restrict");

            $table->foreign('customer_id')
            ->references('customerId')
            ->on('customers')
            ->onUpdate("cascade")
            ->onDelete("restrict");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_transacs');
    }
};
