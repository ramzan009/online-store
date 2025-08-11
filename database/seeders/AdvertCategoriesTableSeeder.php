<?php

namespace Database\Seeders;

use App\Models\Adverts\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdvertCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Category::factory()->count(10)->create()->each(function (Category $category) {
            $counts = [0, random_int(3, 7)];
            $childCount = $counts[array_rand($counts)];

            if ($childCount > 0) {
                // Создаем дочерние категории
                $children = Category::factory()->count($childCount)->create();

                // Связываем их с родителем
                $category->children()->saveMany($children);

                // Для каждой дочерней категории создаем свои дочерние категории
                foreach ($children as $child) {
                    $subCounts = [0, random_int(3, 7)];
                    $subChildCount = $subCounts[array_rand($subCounts)];

                    if ($subChildCount > 0) {
                        $subChildren = Category::factory()->count($subChildCount)->create();
                        $child->children()->saveMany($subChildren);
                    }
                }
            }
        });

    }
}
