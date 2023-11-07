<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'asset_rooms', 'asset_id', 'room_id', 'qty')->withPivot('qty')->withTimestamps();
    }
}
