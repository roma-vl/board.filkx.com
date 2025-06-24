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
        Schema::create('advert_service_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('advert_adverts')->onDelete('cascade');
            $table->string('type'); // 'purchase', 'activate', 'error', 'pdf_generated'
            $table->json('payload')->nullable(); // Дані по замовленню / помилці / послузі
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advert_service_logs');
    }
};
