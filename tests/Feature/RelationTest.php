<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Database\Seeders\WalletSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CustomerSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RelationTest extends TestCase
{
    public function testOneToOne()
    {
        $this->seed(CustomerSeeder::class);
        $this->seed(WalletSeeder::class);

        $customer = Customer::find("BLQS");
        self::assertNotNull($customer);

        $wallet = $customer->wallet;
        self::assertNotNull($wallet);

        $amount = $wallet->amount;
        self::assertEquals(1000000, $amount);
    }

    public function testOneToManyCategory()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("MAKANAN");
        self::assertNotNull($category);

        $products = $category->products;
        self::assertNotNull($products);
        self::assertCount(1, $products);
    }

    public function testOneToManyProducts()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $products = Product::find("1");
        self::assertNotNull($products);

        $category = $products->category;
        self::assertNotNull($category);
        self::assertEquals("MAKANAN", $category->id);
    }
}
