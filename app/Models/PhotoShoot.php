<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoShoot extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => 'bool'
    ];

    protected $guarded = ['id'];

    public function photographerrequest()
    {
        return $this->belongsTo(PhotographerRequest::class, 'photographer_request_id');
    }

    public function getHqFilePathAttribute($value)
    {
        if ($this->status === true) {
            if (!str_contains($value, 'https')) {
                return sprintf("%s/storage/%s", config('app.url'), $value);
            } else {
                return $value;
            }
        }
    }
}
