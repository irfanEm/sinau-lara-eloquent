<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testInsert()
    {
        $category = new Category();
        $category->id = "GORENG";
        $category->name = "GORENGAN";
        $hasil = $category->save();

        self::assertTrue($hasil);
    }

    public function testInsertMany()
    {
        $categories = [];
        for($i = 1; $i <= 10; $i++){
            $categories[] = [
                "id" => "ID $i",
                "name" => "NAME $i"
            ];
        }

        $hasil = Category::insert($categories);
        self::assertTrue($hasil);

        $data = Category::count();
        self::assertEquals(10, $data);
    }
}
