<?php

namespace Database\Seeders;

use App\Models\VirtualAccount;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VirtualAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wallet = Wallet::where("customer_id", "BLQS")->firstOrFail();

        $va = new VirtualAccount();
        $va->bank = "BCA";
        $va->va_number = 010203;
        $va->wallet_id = $wallet->id;
        $va->save();
    }
}
