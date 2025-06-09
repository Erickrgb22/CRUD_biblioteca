<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // Los campos que pueden ser asignados masivamente
    protected $fillable = [
        'title',
        'author',
        'isbn',
    ];

    /**
     * Relación: Un Book tiene muchos Exemplars.
     */
    public function exemplars()
    {
        return $this->hasMany(Exemplar::class);
    }
}