<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'quantity' , 'description' , 'price' , 'sale_price' , 'status'];


    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function productFeaturs()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function offer()
    {
        return $this->hasOne(Offer::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
