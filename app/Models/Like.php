<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Like extends Pivot
{
    protected $table = "customers_likes_products";
    protected $foreignKey = "customer_id";
    protected $relatedKey = "product_id";
    public $timestamps = false;

    public function usesTimestamps()
    {
        return false;
    }

    public function customer(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, "customer_id", "id");
    }

    public function product(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, "product_id", "id");
    }
}
