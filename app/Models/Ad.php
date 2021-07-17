<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'title', 'image', 'ads_url','type', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
