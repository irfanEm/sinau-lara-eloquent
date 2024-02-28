<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    public function testFind()
    {
        $this->seed(CategorySeeder::class);

        $hasil = Category::find("MAKANAN");

        self::assertNotNull($hasil);
        self::assertEquals("MAKANAN", $hasil->id);
        self::assertEquals("MAKANAN", $hasil->name);
        self::assertEquals("Aneka Makanan", $hasil->description);
    }

    public function testUpdate()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("MAKANAN");
        $category->name = "BADOGAN";

        $hasil = $category->update();

        self::assertTrue($hasil);
        self::assertNotNull($category);
        self::assertEquals("BADOGAN", $category->name);
    }

    public function testSelect()
    {
        for($i = 1; $i <= 5; $i++)
        {
            $category = new Category();
            $category->id = "ID $i";
            $category->name = "NAME $i";

            $category->save();
        }

        $hasil = Category::whereNull("description")->get();
        self::assertCount(5, $hasil);
        $hasil -> each(function($sql) {
            Log::info(json_encode($sql));
        });
    }
}
