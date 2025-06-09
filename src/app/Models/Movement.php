<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Carbon\Carbon; // Importar Carbon para manejo de fechas

    class Movement extends Model
    {
        use HasFactory;

        // Asocia el modelo con la tabla 'movements'
        protected $table = 'movements';

        protected $fillable = [
            'user_id',
            'exemplar_id',
            'checkout_date',
            'due_date',
            'return_date',
        ];

        protected $casts = [
            'checkout_date' => 'datetime',
            'due_date' => 'datetime',
            'return_date' => 'datetime',
        ];

        // Relación: Un movimiento pertenece a un usuario (lector)
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        // Relación: Un movimiento es para un ejemplar específico
        public function exemplar()
        {
            return $this->belongsTo(Exemplar::class);
        }

        /**
         * Verifica si el préstamo está vencido (en retraso) y no ha sido devuelto.
         *
         * @return bool
         */
        public function isOverdue()
        {
            // Un préstamo está vencido si no se ha devuelto Y la fecha de vencimiento ya pasó.
            return $this->return_date === null && Carbon::now()->greaterThan($this->due_date);
        }
    }