<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotographerRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function photographer()
    {
        return $this->belongsTo(User::class);
    }

    public function photoshoot()
    {
        return $this->hasMany(PhotoShoot::class);
    }
}
