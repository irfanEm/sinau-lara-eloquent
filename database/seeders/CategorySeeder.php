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
    public function run(): void
    {
        $category = new Category();
        $category->id = "MAKANAN";
        $category->name = "MAKANAN";
        $category->description = "Aneka Makanan";
        $category->is_active = true;
        $category->save();
    }
}
