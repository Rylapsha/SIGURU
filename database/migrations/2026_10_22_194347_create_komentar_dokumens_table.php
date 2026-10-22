
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komentar_dokumens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dokumen_id')
                ->constrained('dokumens')
                ->cascadeOnDelete();

            // ID admin disimpan dari session login admin.
            $table->string('admin_id')->nullable();

            $table->text('komentar');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar_dokumens');
    }
};
