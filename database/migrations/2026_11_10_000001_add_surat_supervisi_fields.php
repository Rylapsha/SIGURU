<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('supervisis')) {
            return;
        }

        Schema::table('supervisis', function (Blueprint $table) {
            if (!Schema::hasColumn('supervisis', 'surat_pemberitahuan_path')) {
                $table->string('surat_pemberitahuan_path')->nullable()->after('status');
            }

            if (!Schema::hasColumn('supervisis', 'nama_surat_pemberitahuan')) {
                $table->string('nama_surat_pemberitahuan')->nullable()->after('surat_pemberitahuan_path');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('supervisis')) {
            return;
        }

        Schema::table('supervisis', function (Blueprint $table) {
            if (Schema::hasColumn('supervisis', 'nama_surat_pemberitahuan')) {
                $table->dropColumn('nama_surat_pemberitahuan');
            }

            if (Schema::hasColumn('supervisis', 'surat_pemberitahuan_path')) {
                $table->dropColumn('surat_pemberitahuan_path');
            }
        });
    }
};
