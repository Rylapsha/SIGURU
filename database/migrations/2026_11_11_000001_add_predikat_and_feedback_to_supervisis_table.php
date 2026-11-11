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
            if (!Schema::hasColumn('supervisis', 'predikat')) {
                $table->string('predikat')->nullable()->after('tindak_lanjut');
            }

            if (!Schema::hasColumn('supervisis', 'feedback_admin')) {
                $table->text('feedback_admin')->nullable()->after('predikat');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('supervisis')) {
            return;
        }

        Schema::table('supervisis', function (Blueprint $table) {
            if (Schema::hasColumn('supervisis', 'feedback_admin')) {
                $table->dropColumn('feedback_admin');
            }

            if (Schema::hasColumn('supervisis', 'predikat')) {
                $table->dropColumn('predikat');
            }
        });
    }
};
