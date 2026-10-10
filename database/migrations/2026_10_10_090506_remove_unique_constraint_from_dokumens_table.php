<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat indeks khusus untuk guru_id terlebih dahulu.
        Schema::table('dokumens', function (Blueprint $table) {
            $table->index('guru_id', 'dokumens_guru_id_index');
        });

        // Setelah itu, hapus batasan unik kategori per guru.
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropUnique(
                'dokumens_guru_id_jenis_dokumen_unique'
            );
        });
    }

    public function down(): void
    {
        // Kembalikan batasan unik jika migration dibatalkan.
        Schema::table('dokumens', function (Blueprint $table) {
            $table->unique(
                ['guru_id', 'jenis_dokumen'],
                'dokumens_guru_id_jenis_dokumen_unique'
            );
        });

        // Hapus indeks tambahan setelah batasan unik dipulihkan.
        Schema::table('dokumens', function (Blueprint $table) {
            $table->dropIndex('dokumens_guru_id_index');
        });
    }
};
