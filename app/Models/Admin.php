<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Admin extends Model
{
    public const ROLES = [
        'kepala_sekolah',
        'kurikulum',
    ];

    protected $table = 'admins';

    protected $fillable = [
        'username',
        'password',
        'role',
        'nama_lengkap',
        'nip',
        'tanggal_lahir',
        'golongan',
        'mata_pelajaran',
        'foto',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class, 'nip', 'nip');
    }

    public function getNamaTanpaGelarAttribute(): string
    {
        $nama = trim((string) $this->nama_lengkap);

        if ($nama === '') {
            return 'Admin';
        }

        $nama = preg_split('/\s*[,;]\s*/u', $nama, 2)[0] ?? $nama;
        $nama = preg_replace('/^(?:(?:Hj|H|Drs|Dra|Dr|Prof|Ir)\.?\s+)/iu', '', $nama) ?? $nama;

        return trim($nama) !== '' ? trim($nama) : 'Admin';
    }
}
