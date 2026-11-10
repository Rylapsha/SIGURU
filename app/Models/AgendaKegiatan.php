<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaKegiatan extends Model
{
    use HasFactory;

    protected $table = 'agenda_kegiatans';

    protected $fillable = [
        'judul',
        'tanggal',
        'jam',
        'lokasi',
        'keterangan',
        'status',
    ];
}
