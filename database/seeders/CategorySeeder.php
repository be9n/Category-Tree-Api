<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $parentCategories = Category::factory(3)->create();

        $parentCategories->each(function ($parent) {
            $this->createChildren($parent, rand(1, 2), 4); // Adjust the depth of the tree as needed
        });
    }

    private function createChildren(Category $parent, $numChildren, $depth)
    {
        if ($depth <= 0) {
            return;
        }

        $children = Category::factory($numChildren)->create(['parent_id' => $parent->id]);

        $children->each(function ($child) use ($depth) {
            $this->createChildren($child, rand(1, 2), $depth - 1);
        });
    }
}
