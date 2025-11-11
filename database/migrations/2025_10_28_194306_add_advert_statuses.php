<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('advert_adverts', function (Blueprint $table) {
            $table->unsignedTinyInteger('status_id')
                ->nullable()
                ->after('status');
        });

        Schema::create('advert_statuses', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('color', 7)->nullable();
            $table->unsignedTinyInteger('state')->unique();
            $table->timestamps();
        });

        $statuses = [
            ['name' => ['uk' => 'Чернетка', 'en' => 'Draft'], 'color' => '#B0BEC5', 'state' => 0, 'slug' => 'draft'],
            ['name' => ['uk' => 'На модерації', 'en' => 'Moderation'], 'color' => '#FBC02D', 'state' => 1, 'slug' => 'moderation'],
            ['name' => ['uk' => 'Активне', 'en' => 'Active'], 'color' => '#4CAF50', 'state' => 2, 'slug' => 'active'],
            ['name' => ['uk' => 'Закрите', 'en' => 'Closed'], 'color' => '#607D8B', 'state' => 3, 'slug' => 'closed'],
            ['name' => ['uk' => 'Не пройшло модерацію', 'en' => 'Moderation failed'], 'color' => '#E57373', 'state' => 4, 'slug' => 'moderation_fail'],
            ['name' => ['uk' => 'Заблоковано', 'en' => 'Banned'], 'color' => '#C62828', 'state' => 5, 'slug' => 'banned'],
            ['name' => ['uk' => 'Видалене', 'en' => 'Deleted'], 'color' => '#9E9E9E', 'state' => 6, 'slug' => 'deleted'],
            ['name' => ['uk' => 'Неактивне (прострочене)', 'en' => 'Expired'], 'color' => '#90A4AE', 'state' => 7, 'slug' => 'expired'],
        ];

        foreach ($statuses as $status) {
            DB::table('advert_statuses')->insert([
                'name' => json_encode($status['name']),
                'color' => $status['color'],
                'state' => $status['state'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $statusMap = DB::table('advert_statuses')->pluck('id', 'state')->toArray();

        DB::table('advert_adverts')->get()->each(function ($advert) use ($statusMap) {
            $state = match ($advert->status) {
                'draft' => 0,
                'moderation' => 1,
                'active' => 2,
                'closed' => 3,
                'moderation_fail' => 4,
                'banned' => 5,
                'deleted' => 6,
                'expired' => 7,
                default => null,
            };

            if ($state !== null && isset($statusMap[$state])) {
                DB::table('advert_adverts')->where('id', $advert->id)->update([
                    'status_id' => $statusMap[$state],
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('advert_adverts', function (Blueprint $table) {
            $table->dropColumn('status_id');
        });

        Schema::dropIfExists('advert_statuses');
    }
};
