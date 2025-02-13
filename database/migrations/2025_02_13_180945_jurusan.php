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
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250)->unique();
            $table->string('label', 250)->nullable();
            $table->timestamps();
        });

        // Insert initial data
        DB::table('jurusan')->insert([
            [
                'id' => 1,
                'name' => 'hubungan_internasional',
                'label' => 'Hubungan Internasional',
            ],
            [
                'id' => 2,
                'name' => 'ilmu_komunikasi',
                'label' => 'Ilmu Komunikasi',
            ],
            [
                'id' => 3,
                'name' => 'ilmu_pemerintahan',
                'label' => 'Ilmu Pemerintahan',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurusan');
    }
};
