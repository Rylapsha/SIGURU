<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomentarDokumen extends Model
{
    protected $table = 'komentar_dokumens';

    protected $fillable = [
        'dokumen_id',
        'admin_id',
        'komentar',
    ];

    public function dokumen(): BelongsTo
    {
        return $this->belongsTo(Dokumen::class, 'dokumen_id');
    }
}
