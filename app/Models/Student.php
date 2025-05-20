<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'id_number',
        'first_name',
        'last_name',
        'email',
        'password',
        'department_name',
        'profile_picture',
        'description',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function councilPositions()
    {
        return $this->hasMany(CouncilPosition::class);
    }
}
