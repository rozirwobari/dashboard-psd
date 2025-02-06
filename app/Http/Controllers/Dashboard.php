<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Jurusan;

class Dashboard extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();
        $mahasiswa = Mahasiswa::all();
        $tahun_range = Mahasiswa::selectRaw('DISTINCT YEAR(tahun_masuk) as tahun')
                ->orderBy('tahun')
                ->pluck('tahun')
                ->toArray();
        $LineChart = $this->GetLineChart($jurusan, $tahun_range);
        $PieChart = $this->GetPieChart($jurusan);
        $Geo = $this->GetGeo($mahasiswa);
        
        return view('content.dashboard', compact('jurusan', 'PieChart', 'LineChart', 'tahun_range', 'Geo'));
    }

    private function GetLineChart($jurusan, $tahun_range) 
    {
        $colors = [
            'rgb(255, 0, 0)',
            'rgba(0, 255, 13, 0.98)',
            'rgb(4, 0, 255)',
        ];
        $mahasiswa = [];
        $number = 0;
        foreach ($jurusan as $key => $value) {
            $datas = [];
            foreach ($tahun_range as $keys => $values) {
                $mhslist = Mahasiswa::where('tahun_masuk', $values)->where('jurusan', $value->id)->get();
                $datas[] = $mhslist->count();
            }
            $mahasiswa[] = [
                'label' => $value->label,
                'data' => $datas,
                'fill' => true,
                'borderColor' => $colors[$number],
                'tension' => 0.4,
                'cubicInterpolationMode' => 'monotone',
                'pointStyle' => 'circle',
                'pointRadius' => 5,
                'pointHoverRadius' => 7
            ];
            $number++;
        }
        // dd($jurusan);
        return $mahasiswa;
    }



    private function GetPieChart($jurusan) 
    {
        $colors = [
            'rgba(255, 0, 55, 0.9)',
            'rgba(0, 248, 12, 0.8)',
            'rgb(4, 0, 255)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)'
        ];
        $mahasiswa = [];
        foreach ($jurusan as $key => $value) {
            $mahasiswa[$value->label] = [
                'label' => $value->label,
                'value' => $value->mahasiswa->count(),
                'color' => $colors[$key],
                'border' => $colors[$key],
            ];
        }
        return $mahasiswa;
    }

    private function GetGeo($mahasiswa) 
    {
        $mahasiswaPerProvinsi = $mahasiswa->groupBy(function($item) {
            return strtoupper($item->provinsi);
        })->map->count();
        return $mahasiswaPerProvinsi;
    }
}
