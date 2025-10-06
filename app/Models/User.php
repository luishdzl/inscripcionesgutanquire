<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'ci',
        'nombres',
        'apellidos',
        'telefono',
        'direccion',
        'fecha_nacimiento',
        'vive_con_representado',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'perfil_completo',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'fecha_nacimiento' => 'date',
            'vive_con_representado' => 'boolean',
        ];
    }

    /**
     * Relación con representados
     */
    public function representados()
    {
        return $this->hasMany(Representado::class);
    }

    /**
     * Accessor para nombre completo
     */
    public function getNombreCompletoAttribute()
    {
        return $this->nombres && $this->apellidos 
            ? $this->nombres . ' ' . $this->apellidos
            : $this->name;
    }

    /**
     * Verificar si el usuario ha completado su perfil de representante
     */
    public function getPerfilCompletoAttribute()
    {
        return !empty($this->ci) && 
               !empty($this->nombres) && 
               !empty($this->apellidos) && 
               !empty($this->telefono) && 
               !empty($this->direccion);
    }
    public function getRepresentadosCountAttribute()
{
    return $this->representados()->count();
}
    
}