<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
