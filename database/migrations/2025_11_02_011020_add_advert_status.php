<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advert_adverts', function (Blueprint $table) {
            $table->boolean('highlight')->default(false)->after('premium');
            $table->boolean('urgent')->default(false)->after('highlight');
            $table->boolean('pin')->default(false)->after('urgent');
        });
    }

    public function down(): void
    {
        Schema::table('advert_adverts', function (Blueprint $table) {
            $table->dropColumn('highlight');
            $table->dropColumn('urgent');
            $table->dropColumn('pin');
        });
    }
};
