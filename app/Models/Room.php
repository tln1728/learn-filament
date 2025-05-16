<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_type_id',
        'room_number',
        'is_available',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function roomtype()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }
}
