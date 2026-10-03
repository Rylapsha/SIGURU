<?php

namespace App\Models;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
