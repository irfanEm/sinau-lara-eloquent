<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Person;
use App\Models\Address;
use App\Models\Product;
use Database\Seeders\ProductSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ImageSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use PhpParser\PrettyPrinter;

class AccesorMutatorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testAccessorMutatorFullName()
    {
        $person = new Person();
        $person->first_name = "Balqis";
        $person->last_name = "Farah";
        $person->save();

        self::assertEquals("BALQIS Farah", $person->full_name);

        $person->full_name = "Balqis Anabila";
        $person->save();

        self::assertEquals("BALQIS", $person->first_name);
        self::assertEquals("Anabila", $person->last_name);
    }

    public function testCastsAttr()
    {
        $person = new Person();
        $person->first_name = "Balqis";
        $person->last_name = "Farah";
        $person->save();

        self::assertNotNull($person->created_at);
        self::assertNotNull($person->updated_at);
        self::assertInstanceOf(Carbon::class, $person->created_at);
        self::assertInstanceOf(Carbon::class, $person->updated_at);
    }

    public function testCastAddress()
    {
        $person = new Person();
        $person->first_name = "Balqis";
        $person->last_name = "Farah";
        $person->address = new Address("Jalan yang lurus", "Petunjuk", "Kebenaran", "53252");
        $person->save();

        $person = Person::find($person->id);
        self::assertNotNull($person);
        self::assertInstanceOf(Address::class, $person->address);
        self::assertEquals("Jalan yang lurus", $person->address->street);
        self::assertEquals("Petunjuk", $person->address->city);
        self::assertEquals("Kebenaran", $person->address->country);
        self::assertEquals("53252", $person->address->postal_code);
    }

    public function testSerialization()
    {
        $this->seed([CategorySeeder::class, ProductSeeder::class, ImageSeeder::class]);

        $products = Product::get();
        $products->load(['category', 'image']);
        self::assertCount(2, $products);

        $json = $products->toJson(JSON_PRETTY_PRINT);
        Log::info($json);
    }
}
