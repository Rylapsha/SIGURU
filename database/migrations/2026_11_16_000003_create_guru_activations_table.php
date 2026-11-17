<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_activations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->unique()->constrained('gurus')->cascadeOnDelete();
            $table->foreignId('generated_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('code_hash');
            $table->char('code_fingerprint', 64)->unique();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_activations');
    }
};
