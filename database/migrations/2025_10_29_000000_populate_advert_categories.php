<?php

use App\Models\Adverts\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB; // має використовувати NestedSet

return new class extends Migration
{
    public function up(): void
    {
        DB::table('advert_categories')->truncate();

        $data = json_decode(file_get_contents(database_path('data/advert_categories.json')), true);

        $this->buildTree(null, $data);
    }

    private function buildTree(?int $parentId, array $nodes): void
    {
        foreach ($nodes as $node) {
            $category = Category::create([
                'id' => $node['id'],
                'name' => $node['name'],
                'slug' => $node['slug'],
                'parent_id' => $parentId,
            ]);

            if (! empty($node['children'])) {
                $this->buildTree($category->id, $node['children']);
            }
        }
    }

    public function down(): void
    {
        DB::table('advert_categories')->truncate();
    }
};
