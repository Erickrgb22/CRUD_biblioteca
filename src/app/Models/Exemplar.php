<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exemplar extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'exemplar_state_id',
        'location',
    ];

    /**
     * Define la relación: Un ejemplar pertenece a un libro.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Define la relación: Un ejemplar tiene un estado.
     * Esta es la relación que faltaba.
     */
    public function state()
    {
        // Un Exemplar pertenece a un ExemplarState.
        // Laravel por defecto buscará 'exemplar_state_id' en la tabla 'exemplars'
        // y 'id' en la tabla 'exemplar_states'.
        // Si tu clave foránea se llamara diferente (ej. 'state_id'), tendrías que especificarla aquí:
        // return $this->belongsTo(ExemplarState::class, 'nombre_de_tu_clave_foranea');
        return $this->belongsTo(ExemplarState::class, 'exemplar_state_id');
    }

    /**
     * Define la relación: Un ejemplar puede tener muchos movimientos (préstamos/devoluciones).
     * (Esta relación la usaremos más adelante en el módulo de Préstamos).
     */
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}