<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supervisi extends Model
{
    protected $table = 'supervisis';

    protected $fillable = [
        'guru_id',
        'nama_supervisor',
        'tanggal',
        'jam',
        'mata_pelajaran',
        'semester',
        'keterangan',
        'status',
        'hasil_observasi',
        'tindak_lanjut',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
