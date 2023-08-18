<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'from_room_id',
        'to_room_id',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
