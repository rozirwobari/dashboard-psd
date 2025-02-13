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
        Schema::create('data_pending', function (Blueprint $table) {
            $table->id();
            $table->string('nim', 50)->nullable();
            $table->string('nama', 100)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('jurusan', 50)->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kabupaten_kota', 100)->nullable();
            $table->timestamps(); // Menambahkan created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pending');
    }
};
