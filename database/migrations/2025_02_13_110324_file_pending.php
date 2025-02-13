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
        Schema::create('file_pending', function (Blueprint $table) {
            $table->id(); // Ini akan membuat kolom id sebagai auto increment
            $table->mediumText('file_name')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps(); // Menambahkan created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_pending');
    }
};
