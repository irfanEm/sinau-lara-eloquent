<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Scopes\IsActiveScope;
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
            $sql->description = "Di Update";
            $sql->update();
            Log::info(json_encode($sql));
        });
    }

    public function testUpdateMany()
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

        Category::whereNull("description")->update([
            "description" => "Di Update"
        ]);

        $total = Category::where("description", "!=", "null")->count();
        self::assertEquals(10, $total);
    }

    public function testDelete()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find("MAKANAN");
        $hasil = $category->delete();
        self::assertTrue($hasil);

        $category = Category::count();
        self::assertEquals(0, $category);
    }

    public function testDeleteMany()
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

        $total = Category::count();
        self::assertEquals(10, $total);

        Category::whereNull("description")->delete();

        $total = Category::count();
        self::assertEquals(0, $total);
    }

    public function testFillable()
    {
        $request = [
            "id" => "BADOGAN",
            "name" => "BADOGAN",
            "description" => "Aneka Makanan"
        ];

        $category = new Category($request);
        $category->save();

        self::assertNotNull($category->id);
        self::assertNotNull($category->name);
        self::assertNotNull($category->description);
    }

    public function testCreateMethod()
    {
        $request = [
            "id" => "BADOGAN",
            "name" => "BADOGAN",
            "description" => "Aneka Makanan"
        ];

        $category = Category::create($request);

        self::assertNotNull($category->id);
        self::assertNotNull($category->name);
        self::assertNotNull($category->description);
    }

    public function testFillMethod()
    {
        $this->seed(CategorySeeder::class);

        $request = [
            "name" => "PERBADOGAN",
            "description" => "Aneka Perbadogan Duniawi"
        ];

        $category = Category::find("MAKANAN");
        $category->fill($request);
        $category->save();

        $hasil = Category::find("MAKANAN");
        self::assertEquals("PERBADOGAN", $hasil->name);
        self::assertEquals("Aneka Perbadogan Duniawi", $hasil->description);
    }

    public function testGlobalScope()
    {
        $category = new Category();
        $category->id = "MKNN";
        $category->name = "MAKANAN";
        $category->description = "Aneka Makanan";
        $category->is_active = false;
        $simpan = $category->save();
        self::assertTrue($simpan);

        $hasil = Category::find("MKNN");
        self::assertNull($hasil);

        $hasil = Category::withoutGlobalScopes([IsActiveScope::class])->find("MKNN");
        self::assertNotNull($hasil);
    }
}
