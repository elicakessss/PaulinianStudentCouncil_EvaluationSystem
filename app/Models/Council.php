<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Council extends Model
{
    public function adviser() {
    return $this->belongsTo(Adviser::class);
}

public function department() {
    return $this->belongsTo(Department::class);
}

public function councilPositions() {
    return $this->hasMany(CouncilPosition::class);
}

// Check if this is a university-wide council
public function isUniversityWide() {
    return is_null($this->department_id);
}
}
