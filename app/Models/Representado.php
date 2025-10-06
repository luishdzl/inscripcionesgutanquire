<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representado extends Model
{
    use HasFactory;

    protected $fillable = [
        'ci',
        'fecha_nacimiento',
        'nombres',
        'apellidos',
        'telefono',
        'direccion',
        'nivel_academico',
        'foto',
        'user_id' // Cambiado de representante_id a user_id
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Relación con el usuario (representante)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombres . ' ' . $this->apellidos;
    }
}