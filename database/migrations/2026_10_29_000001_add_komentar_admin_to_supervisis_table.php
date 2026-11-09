<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('supervisis', function (Blueprint $table) {
            if (!Schema::hasColumn('supervisis', 'komentar_admin')) {
                $table->text('komentar_admin')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supervisis', function (Blueprint $table) {
            if (Schema::hasColumn('supervisis', 'komentar_admin')) {
                $table->dropColumn('komentar_admin');
            }
        });
    }
};
