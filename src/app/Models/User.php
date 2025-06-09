<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Los campos que pueden ser asignados masivamente
    protected $fillable = [
        'user_type_id',
        'name',
        'ci',
        'email',
        'phone',
        'password',
    ];

    // Los campos que deben estar ocultos para serialización (por ejemplo, en JSON)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Los campos que deben ser casteados a tipos nativos
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Asegura que la contraseña siempre se guarde hasheada
    ];

    /**
     * Relación: Un User pertenece a un UserType.
     */
    public function userType()
    {
        return $this->belongsTo(UserType::class);
    }

    /**
     * Relación: Un User tiene muchos Movements (préstamos/devoluciones).
     */
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}