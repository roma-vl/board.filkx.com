<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advert_boosts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('advert_adverts')->onDelete('cascade');
            $table->string('type'); // highlight, vip, pin, urgent
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamps();
        });

        Schema::create('advert_boost_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('features'); // ["highlight", "vip", "pin", "urgent"]
            $table->integer('duration_days');
            $table->decimal('price', 8, 2);
            $table->boolean('social_share')->default(false);
            $table->integer('auto_lift_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advert_boosts');
        Schema::dropIfExists('advert_boost_packages');
    }
};
