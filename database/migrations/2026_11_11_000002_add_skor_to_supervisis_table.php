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
            if (!Schema::hasColumn('supervisis', 'skor')) {
                $table->unsignedTinyInteger('skor')->nullable()->after('predikat');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('supervisis')) {
            return;
        }

        Schema::table('supervisis', function (Blueprint $table) {
            if (Schema::hasColumn('supervisis', 'skor')) {
                $table->dropColumn('skor');
            }
        });
    }
};
