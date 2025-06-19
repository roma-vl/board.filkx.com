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
        Schema::create('advert_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('advert_adverts');
            $table->string('service_type');
            $table->decimal('price', 8, 2);
            $table->enum('status', ['pending', 'paid', 'failed'])->default('paid');
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advert_orders');
    }
};
