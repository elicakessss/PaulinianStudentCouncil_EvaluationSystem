<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public function councilPositions() {
    return $this->hasMany(CouncilPosition::class);
}
}
