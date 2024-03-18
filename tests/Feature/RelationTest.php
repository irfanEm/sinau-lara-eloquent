<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\VirtualAccount;
use App\Models\Wallet;
use Database\Seeders\WalletSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\ImageSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\VirtualAccountSeeder;
use Database\Seeders\VoucherSeeder;
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

    public function testRelationHasOfMany()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class]);
        $category = Category::find("MAKANAN");

        $productTermurah = $category->productTermurah;
        self::assertNotNull($productTermurah);
        self::assertEquals(0, $productTermurah->price);

        $productTermahal = $category->productTermahal;
        self::assertNotNull($productTermahal);
        self::assertEquals(300, $productTermahal->price);

    }

    public function testHasOneThrough()
    {
        $this->seed([CustomerSeeder::class, WalletSeeder::class, VirtualAccountSeeder::class]);

        $customer = Customer::find("BLQS");
        self::assertNotNull($customer);

        $virtualAccount = $customer->virtualAccount;
        self::assertNotNull($virtualAccount);
        self::assertEquals("BCA", $virtualAccount->bank);
    }

    public function testHasManyThrought()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, CustomerSeeder::class, ReviewSeeder::class]);

        $category = Category::find("MAKANAN");
        self::assertNotNull($category);

        $reviews = $category->reviews;
        self::assertNotNull($reviews);
        self::assertCount(2, $reviews);
    }

    public function testManyToManyAttach()
    {
        $this->seed([CustomerSeeder::class, CategorySeeder::class, ProductSeeder::class]);

        $customer = Customer::find("BLQS");
        self::assertNotNull($customer);

        $customer->likeProducts()->attach("1");

        $product = $customer->likeProducts;
        self::assertCount(1, $product);
        self::assertEquals(1, $product[0]->id);
    }

    public function testManyToManyDetach()
    {
        $this->testManyToManyAttach();

        $customer = Customer::find("BLQS");
        $customer->likeProducts()->detach("1");

        $product = $customer->likeProducts;

        self::assertNotNull($product);
        self::assertCount(0, $product);
    }

    public function testPivot()
    {
        $this->testManyToManyAttach();

        $customer = Customer::find("BLQS");
        $products = $customer->likeProducts;

        // dd($products);

        foreach($products as $product){
            $pivot = $product->pivot;
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }
    }

    public function testWherePivotAttribut()
    {
        $this->testManyToManyAttach();

        $customer = Customer::find("BLQS");
        $products = $customer->likeProductsLastWeek;

        // dd($products);

        foreach($products as $product){
            $pivot = $product->pivot;
            self::assertNotNull($pivot);
            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);
        }
    }

    public function testPivotModel()
    {
        $this->testManyToManyAttach();

        $customer = Customer::find("BLQS");
        $products = $customer->likeProducts;

        foreach($products as $product){
            $pivot = $product->pivot;
            self::assertNotNull($pivot);

            self::assertNotNull($pivot->customer_id);
            self::assertNotNull($pivot->product_id);
            self::assertNotNull($pivot->created_at);

            self::assertNotNull($pivot->product);
            self::assertNotNull($pivot->customer);
        }
    }

    public function testImages()
    {
        $this->seed([CustomerSeeder::class, ImageSeeder::class]);

        $customer = Customer::find("BLQS");
        self::assertNotNull($customer);

        $image = $customer->image;
        self::assertNotNull($image);
        self::assertEquals("https://instagram.com/irfan.em/1.jpg", $image->url);
    }

    public function testProductMorph()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);

        $product = Product::find("1");
        self::assertNotNull($product);

        $image = $product->image;
        self::assertNotNull($image);
        self::assertEquals("https://instagram.com/irfan.em/2.jpg", $image->url);
    }

    public function testOneToManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $product = Product::first();
        self::assertNotNull($product);

        $comments = $product->comments;
        foreach($comments as $comment){
            self::assertEquals($product->id, $comment->commentable_id);
            self::assertEquals(Product::class, $comment->commentable_type);
        }
    }

    public function testOneOfManyPolymorphic()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, VoucherSeeder::class, CommentSeeder::class]);

        $product = Product::find("1");
        self::assertNotNull($product);

        $comment = $product->komentTerbaru;
        self::assertNotNull($comment);

        $commentLama = $product->komentTerlama;
        self::assertNotNull($commentLama);
    }
}
