<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoom extends Model
{
    use HasFactory;

    protected $fillable = ['room_id', 'asset_id', 'qty'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
