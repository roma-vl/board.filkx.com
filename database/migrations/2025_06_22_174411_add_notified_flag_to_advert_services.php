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
        Schema::table('advert_services', function (Blueprint $table) {
            $table->boolean('notified_before_expire')->default(false);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advert_services', function (Blueprint $table) {
            $table->dropColumn('notified_before_expire');
        });
    }
};
