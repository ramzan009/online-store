<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Region::factory()->count(10)->create()->each(function (Region $region) {

            $children = Region::factory()->count(random_int(3, 10))->create();

            $children->each(function (Region $child) {
               $grandchildren = Region::factory()->count(random_int(3, 10))->make();
               $child->children()->saveMany($grandchildren);
            });

            $region->children()->saveMany($children);
        });
    }
}
