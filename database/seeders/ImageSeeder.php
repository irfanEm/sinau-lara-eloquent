<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $image1 = new Image();
        $image1->url = "https://instagram.com/irfan.em/1.jpg";
        $image1->imageable_id = "BLQS";
        $image1->imageable_type = 'customer';
        $image1->save();

        $image2 = new Image();
        $image2->url = "https://instagram.com/irfan.em/2.jpg";
        $image2->imageable_id = "1";
        $image2->imageable_type = "product";
        $image2->save();
    }
}
