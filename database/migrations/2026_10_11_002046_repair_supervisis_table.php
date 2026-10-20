
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel apabila belum ada.
        if (!Schema::hasTable('supervisis')) {
            Schema::create('supervisis', function (Blueprint $table) {
                $table->id();

                $table->foreignId('guru_id')
                    ->nullable()
                    ->constrained('gurus')
                    ->cascadeOnDelete();

                $table->string('nama_supervisor')->nullable();
                $table->date('tanggal')->nullable();
                $table->time('jam')->nullable();
                $table->string('mata_pelajaran')->nullable();
                $table->string('semester')->nullable();
                $table->text('keterangan')->nullable();
                $table->string('status')->default('terjadwal');
                $table->text('hasil_observasi')->nullable();
                $table->text('tindak_lanjut')->nullable();

                $table->timestamps();
            });

            return;
        }

        // Lengkapi kolom jika tabel sudah ada.
        if (!Schema::hasColumn('supervisis', 'guru_id')) {
            Schema::table('supervisis', function (Blueprint $table) {
                $table->foreignId('guru_id')
                    ->nullable()
                    ->constrained('gurus')
                    ->cascadeOnDelete();
            });
        }

        $columns = [
            'nama_supervisor' => fn(Blueprint $table) => $table->string('nama_supervisor')->nullable(),
            'tanggal' => fn(Blueprint $table) => $table->date('tanggal')->nullable(),
            'jam' => fn(Blueprint $table) => $table->time('jam')->nullable(),
            'mata_pelajaran' => fn(Blueprint $table) => $table->string('mata_pelajaran')->nullable(),
            'semester' => fn(Blueprint $table) => $table->string('semester')->nullable(),
            'keterangan' => fn(Blueprint $table) => $table->text('keterangan')->nullable(),
            'status' => fn(Blueprint $table) => $table->string('status')->default('terjadwal'),
            'hasil_observasi' => fn(Blueprint $table) => $table->text('hasil_observasi')->nullable(),
            'tindak_lanjut' => fn(Blueprint $table) => $table->text('tindak_lanjut')->nullable(),
            'created_at' => fn(Blueprint $table) => $table->timestamp('created_at')->nullable(),
            'updated_at' => fn(Blueprint $table) => $table->timestamp('updated_at')->nullable(),
        ];

        foreach ($columns as $column => $definition) {
            if (!Schema::hasColumn('supervisis', $column)) {
                Schema::table('supervisis', $definition);
            }
        }
    }

    public function down(): void
    {
        // Tidak menghapus tabel atau kolom saat rollback.
    }
};
