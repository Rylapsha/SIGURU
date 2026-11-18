<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->date('tanggal_lahir')->nullable()->after('nip');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->date('tanggal_lahir')->nullable()->after('nip');
        });
    }

    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn('tanggal_lahir');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('tanggal_lahir');
        });
    }
};
