<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $fillable = ['name', 'slug'];

    public function GameMatchs(): HasMany
    {
        return $this->hasMany(GameMatch::class);
    }
}
