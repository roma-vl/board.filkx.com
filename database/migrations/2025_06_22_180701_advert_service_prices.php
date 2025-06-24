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
        Schema::create('advert_service_prices', function (Blueprint $table) {
            $table->id();
            $table->string('service_type'); // 'highlight', 'turbo30' тощо
            $table->decimal('price', 8, 2);
            $table->string('currency')->default('UAH');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advert_service_prices');
    }
};
