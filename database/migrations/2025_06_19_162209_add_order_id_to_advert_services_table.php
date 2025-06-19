<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advert_services', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('auto_ups_left');

            $table->foreign('order_id')->references('id')->on('advert_orders')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('advert_services', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
    }
};
