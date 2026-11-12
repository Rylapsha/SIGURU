<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('nama_lengkap', 150)->nullable()->after('username');
            $table->string('nip', 30)->nullable()->after('nama_lengkap');
            $table->string('golongan', 50)->nullable()->after('nip');
            $table->string('mata_pelajaran', 100)->nullable()->after('golongan');
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'nama_lengkap',
                'nip',
                'golongan',
                'mata_pelajaran',
            ]);
        });
    }
};
