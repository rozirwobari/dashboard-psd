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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 50)->unique();
            $table->string('nama', 100);
            $table->string('tempat_lahir', 100);
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat');
            $table->foreignId('jurusan')
                  ->default(1)
                  ->constrained('jurusan')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->year('tahun_masuk');
            $table->string('provinsi', 100);
            $table->string('kabupaten_kota', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
