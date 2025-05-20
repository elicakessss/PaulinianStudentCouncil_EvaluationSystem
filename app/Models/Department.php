<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'abbreviation'];

    public function advisers()
    {
        return $this->hasMany(Adviser::class);
    }

    public function councils()
    {
        return $this->hasMany(Council::class);
    }
}
