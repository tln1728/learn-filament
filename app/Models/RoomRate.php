<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'date',
        'price',
        'is_promotion',
    ];

    public function roomtype()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
