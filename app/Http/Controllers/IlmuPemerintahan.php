<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Jurusan;
use Illuminate\Support\Facades\Http;

class IlmuPemerintahan extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = "http://localhost:2003";
    }

    public function index() 
    {
        $jurusan = Jurusan::find(3);
        $daftar_mahasiswa = $jurusan->mahasiswa;
        $tahun_range = $jurusan->mahasiswa()
            ->selectRaw('DISTINCT YEAR(tahun_masuk) as tahun')
            ->orderBy('tahun')
            ->pluck('tahun')
            ->toArray();

        $colors = [
            'rgba(255, 99, 132, 0.8)',
            'rgba(248, 0, 54, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)'
        ];
        $pieChart = [];
        foreach ($tahun_range as $key => $tahun) {
            $pieChart[$tahun] = [
                'value' => $jurusan->mahasiswa()
                    ->whereYear('tahun_masuk', $tahun)
                    ->count(),
                'color' => $colors[$key % count($colors)],
                'border' => $colors[$key % count($colors)],
            ];
        }

        $mahasiswa_per_tahun = $jurusan->mahasiswa()
                            ->selectRaw('YEAR(tahun_masuk) as tahun, COUNT(*) as total')
                            ->groupBy('tahun')
                            ->orderBy('tahun')
                            ->pluck('total')
                            ->values()
                            ->toArray();

        
        $mahasiswaPerProvinsi = $daftar_mahasiswa->groupBy(function($item) {
            return strtoupper($item->provinsi);
        })->map->count();
        // dd($mahasiswaPerProvinsi);

        $prediction = Http::post($this->baseUrl . '/ilpem');
        $prediction = $prediction->json();
        return view('content.ilmu_pemerintahan.index', compact('pieChart', 'tahun_range', 'mahasiswa_per_tahun', 'mahasiswaPerProvinsi', 'prediction'));
    }
}
