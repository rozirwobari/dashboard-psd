<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Jurusan;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $guarded = [];

    public function jurusan()
    {
        // saya menggunakan belongsto karena setiap mahasiswa hanya memiliki satu jurusan
        // dan satu jurusan bisa memiliki banyak mahasiswa
        return $this->belongsTo(Jurusan::class, 'jurusan', 'id');
    }
}
