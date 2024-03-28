<?php

namespace Tests\Feature;

use App\Models\Comment;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function testComments()
    {
        $comment = new Comment();
        $comment -> email = "contoh@email.com";
        $comment -> title = "Contoh Judul";
        $comment -> comment = "Contoh komentar.";
        $comment->commentable_id = "1";
        $comment->commentable_type = "product";

        $comment -> save();

        self::assertNotNull($comment);
    }

    public function testAttributesValues()
    {
        $comment = new Comment();
        $comment -> email = "contoh@email.com";
        $comment->commentable_id = "1";
        $comment->commentable_type = "product";

        $comment -> save();

        self::assertNotNull($comment->id);
        self::assertNotNull($comment->title);
        self::assertNotNull($comment->comment);
    }
}
