<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';

    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'nip',
        'golongan',
        'mata_pelajaran',
    ];

    protected $hidden = [
        'password',
    ];
}
