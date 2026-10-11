<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('supervisis', 'guru_id')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->foreignId('guru_id')
                    ->nullable()
                    ->constrained('gurus')
                    ->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn('supervisis', 'nama_supervisor')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->string('nama_supervisor')->nullable();
            });
        }

        if (!Schema::hasColumn('supervisis', 'tanggal')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->date('tanggal')->nullable();
            });
        }

        if (!Schema::hasColumn('supervisis', 'jam')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->time('jam')->nullable();
            });
        }

        if (!Schema::hasColumn('supervisis', 'mata_pelajaran')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->string('mata_pelajaran')->nullable();
            });
        }

        if (!Schema::hasColumn('supervisis', 'semester')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->string('semester')->nullable();
            });
        }

        if (!Schema::hasColumn('supervisis', 'keterangan')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->text('keterangan')->nullable();
            });
        }

        if (!Schema::hasColumn('supervisis', 'status')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->string('status')->default('terjadwal');
            });
        }

        if (!Schema::hasColumn('supervisis', 'hasil_observasi')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->text('hasil_observasi')->nullable();
            });
        }

        if (!Schema::hasColumn('supervisis', 'tindak_lanjut')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->text('tindak_lanjut')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Tidak menghapus kolom agar data tidak hilang saat rollback.
    }
};