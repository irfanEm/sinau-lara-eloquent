<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Wallet;
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

    public function testOneToOneQuery()
    {
        $customer = new Customer();
        $customer->id = "BLQS";
        $customer->name = "Balqis FA";
        $customer->email = "balqis@email.com";
        $customer->save();

        $wallet = new Wallet();
        $wallet->amount = 10000000;
        $customer->wallet()->save($wallet);

        self::assertNotNull($wallet->customer_id);
        self::assertNotNull($customer);
    }

    public function testOneToManyQuery()
    {
        $category = new Category();
        $category->id = "PLSA";
        $category->name = "Pulsa";
        $category->description = "Pulsa All Operator";
        $category->is_active = true;
        $category->save();

        $product = new Product();
        $product->id = "1";
        $product->name = "Pulsa Telkomsel";
        $category->products()->save($product);

        self::assertNotNull($product->category_id);
        self::assertEquals("PLSA", $product->category_id);
    }

    public function testRelationshipQuery()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);

        $category = Category::find("MAKANAN");
        $products = $category->products;
        self::assertCount(1, $products);

        $productHabis = $category->products()->where("stock", "<=", 0)->get();
        self::assertCount(1, $productHabis);
    }
}
