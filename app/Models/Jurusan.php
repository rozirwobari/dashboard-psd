<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;

class Jurusan extends Model
{
    protected $table = 'jurusan';
    protected $guarded = [];

    public function mahasiswa()
    {
        // saya menggunakan hasMany karena satu jurusan dapat memiliki banyak mahasiswa
        return $this->hasMany(Mahasiswa::class, 'jurusan', 'id');
    }
}
