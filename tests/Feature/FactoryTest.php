<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FactoryTest extends TestCase
{
    public function testEmployeeFactory()
    {
        $employee1 = Employee::factory()->programmer()->make();
        $employee1->id = "1";
        $employee1->name = "Balqis FA";
        $employee1->save();

        $balqis = Employee::find("1")->first();
        self::assertNotNull($balqis);
        self::assertEquals("Balqis FA", $balqis->name);
        self::assertEquals("programmer", $balqis->title);
        self::assertEquals("5000000", $balqis->salary);

        $employee2 = Employee::factory()->seniorProgrammer()->create([
            "id" => "2",
            "name" => "Irfan M"
        ]);

        $irfan = Employee::find("2");
        self::assertNotNull($irfan);
        self::assertEquals("Irfan M", $irfan->name);
        self::assertEquals("Senior programmer", $irfan->title);
        self::assertEquals("15000000", $irfan->salary);
    }
}
