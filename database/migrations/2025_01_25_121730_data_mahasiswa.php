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
            $table->integer('nim');
            $table->string('nama', 100);
            $table->string('tempat_lahir', 100); 
            $table->string('jenis_kelamin', 10);
            $table->text('alamat');
            $table->string('jurusan', 100);
            $table->year('tahun_masuk');
            $table->string('provinsi', 100);
            $table->string('kabupaten_kota', 100);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
