<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function advisers() {
        return $this->hasMany(Adviser::class);
    }

    public function councils() {
        return $this->hasMany(Council::class);
    }
}
