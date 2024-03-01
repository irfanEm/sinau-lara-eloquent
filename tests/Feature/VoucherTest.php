<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Voucher;
use Database\Seeders\VoucherSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoucherTest extends TestCase
{
    public function testUUID()
    {
        $voucher = $this->seed(VoucherSeeder::class);

        self::assertNotNull($voucher);
    }

    public function testCreateVoucherUuid()
    {
        $voucher = new Voucher();
        $voucher->name = "Voucher Contoh";
        $voucher->save();

        self::assertNotNull($voucher->id);
        self::assertNotNull($voucher->voucher_code);
    }

    public function testSoftDelete()
    {
        $this->seed(VoucherSeeder::class);

        $voucher = Voucher::where("name", "=", "Contoh Voucher")->first();
        $voucher->delete();

        $voucher = Voucher::where("name", "=", "Contoh Voucher")->first();
        self::assertNull($voucher);

        $voucher = Voucher::withTrashed()->where("name", "=", "Contoh Voucher")->first();
        self::assertNotNull($voucher);
    }

    public function testLocalScope()
    {
        $voucher = new Voucher();
        $voucher->name = "Contoh Voucher";
        $voucher->is_active = true;
        $voucher->save();

        $hasil = Voucher::active()->count();
        self::assertEquals(1, $hasil);

        $hasil = Voucher::nonActive()->count();
        self::assertEquals(0, $hasil);
    }
}
