<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouncilPosition extends Model
{
    public function council() {
    return $this->belongsTo(Council::class);
}

public function position() {
    return $this->belongsTo(Position::class);
}

public function student() {
    return $this->belongsTo(Student::class);
}
}
