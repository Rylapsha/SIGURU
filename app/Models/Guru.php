<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guru extends Authenticatable
{
    use Notifiable;

    protected $table = 'gurus';

    protected $fillable = [
        'nip',
        'nama_lengkap',
        'golongan',
        'mata_pelajaran',
        'password',
        'foto',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function activation(): HasOne
    {
        return $this->hasOne(GuruActivation::class);
    }
}
