<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_type_id',
        'item_code',
        'name',
        'acquition',
        'total',
        'last_move_date',
        'condition',
        'note',
    ];

    public function asset_type(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }

    public function assetRoomConditions(): HasMany
    {
        return $this->hasMany(AssetRoomCondition::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }
}
