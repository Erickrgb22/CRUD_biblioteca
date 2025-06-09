<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExemplarState extends Model
{
    use HasFactory;

    // Los campos que pueden ser asignados masivamente
    protected $fillable = ['state'];

    /**
     * Relación: Un ExemplarState tiene muchos Exemplars.
     */
    public function exemplars()
    {
        return $this->hasMany(Exemplar::class);
    }
}