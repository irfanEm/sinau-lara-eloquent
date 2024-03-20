<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    protected $table = "tags";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $incrementing = false;
    public $timestamps = false;

    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, "taggable");
    }

    public function vouchers(): MorphToMany
    {
        return $this->morphedByMany(Voucher::class, "taggable");
    }
}
