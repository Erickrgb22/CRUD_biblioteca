<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    // Los campos que pueden ser asignados masivamente
    protected $fillable = ['type'];

    /**
     * Relación: Un UserType tiene muchos Users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}