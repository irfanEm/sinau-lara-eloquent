<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createCommentForProduct();
        $this->createCommentForVoucher();
    }

    protected function createCommentForProduct(): void
    {
        $product = Product::find("1");
        $comment = new Comment();
        $comment->email = "test@mail.com";
        $comment->title = "Judul";
        $comment->commentable_id = $product->id;
        $comment->commentable_type = Product::class;
        $comment->save();
    }

    protected function createCommentForVoucher(): void
    {
        $voucher = Voucher::first();
        $comment = new Comment();
        $comment->email = "test@mail.com";
        $comment->title = "Judul";
        $comment->commentable_id = $voucher->id;
        $comment->commentable_type = Voucher::class;
        $comment->save();
    }
}

