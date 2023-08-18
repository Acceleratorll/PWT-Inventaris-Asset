<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_type_id',
        'room_id',
        'item_code',
        'name',
        'acquition',
        'isMoveable',
        'total',
        'last_move_date',
        'condition',
        'note',
    ];

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function asset_type()
    {
        return $this->belongsTo(AssetType::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
