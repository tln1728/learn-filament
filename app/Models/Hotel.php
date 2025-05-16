<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'address',
        'city',
        'country',
        'zip_code',
        'phone',
        'email',
        'website',
    ];

    public function rommTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // public function rooms()
    // {
    //     return $this->hasManyThrough(Room::class, RoomType::class);
    // }
}
