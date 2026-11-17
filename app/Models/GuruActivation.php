<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuruActivation extends Model
{
    protected $fillable = [
        'guru_id',
        'generated_by',
        'code_hash',
        'code_fingerprint',
        'attempts',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'attempts' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'generated_by');
    }
}
