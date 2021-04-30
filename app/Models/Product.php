<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function photographrequest()
    {
        return $this->hasOne(PhotographerRequest::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(fn ($product) => $product->uuid = Str::uuid());
    }
}
