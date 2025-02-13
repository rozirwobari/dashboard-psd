<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Jurusan;
use App\Models\DataPendingModel;
use App\Models\FilePendingModel;
use Illuminate\Support\Facades\Http;

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

    public function upload() 
    {
        $DataPending = DataPendingModel::all();
        $FilePending = FilePendingModel::all();
        return view('content.upload', compact('DataPending', 'FilePending'));
    }

    public function insertData(Request $request) 
    {
        $this->validateRequest($request);
        try {
            if (!$request->hasFile('data_mahasiswa')) {
                return $this->errorResponse('Tidak ada file yang diupload');
            }
            
            $file = $request->file('data_mahasiswa');
            
            if (!$this->isValidCsvFile($file)) {
                return $this->errorResponse('File harus berformat CSV');
            }
            
            $fileContent = file_get_contents($file->getPathname());
            $filenames = time() . '_' . $file->getClientOriginalName();
            $response = Http::attach(
                'file',
                file_get_contents($file->getPathname()),
                $filenames
            )->post('http://127.0.0.1:2003/insertdata');

            
            if ($response->successful()) {
                return $this->handleSuccessfulUpload($file, $response, $filenames);
            }
            
            return $this->errorResponse('Gagal memproses file di Python API: ' . $response->body());

        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage());
            return $this->errorResponse('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'data_mahasiswa' => [
                'required',
                'file',
                'mimetypes:text/csv,text/plain,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'max:204800'
            ]
        ], [
            'data_mahasiswa.required' => 'File harus diupload',
            'data_mahasiswa.mimes' => 'File harus berformat CSV',
            'data_mahasiswa.max' => 'Ukuran file maksimal 200MB'
        ]);
    }

    private function isValidCsvFile($file)
    {
        $allowedMimeTypes = [
            'text/csv',
            'application/csv',
            'text/plain'
        ];
        
        return in_array($file->getMimeType(), $allowedMimeTypes);
    }

    private function sendToPythonApi($file)
    {
        $response = Http::attach(
            'file',
            file_get_contents($file->getPathname()),
            $file->getClientOriginalName()
        )->post('http://127.0.0.1:2003/insertdata');
    }

    private function handleSuccessfulUpload($file, $response, $fileName)
    {
        // Generate unique filename with timestamp
        $path = $file->storeAs('uploads/csv', $fileName, 'public');

        FilePendingModel::insert([
            'file_name' => $fileName,
        ]);

        // Store Python response in session if needed
        session()->flash('alert', [
            'title' => 'Berhasil',
            'message' => 'File berhasil diupload dan diproses',
            'type' => 'success'
        ]);

        return back()->with('success', 'File berhasil diupload dan diproses')
            ->with('filePath', $path);
    }

    private function errorResponse($message)
    {
        return back()
            ->with('error', $message)
            ->withInput();
    }

    public function InsertNewData()
    {
        try {
            DataPendingModel::chunk(1000, function ($records) {
                $dataToInsert = [];
                $processedIds = [];
                
                foreach ($records as $data) {
                    $idJurusan = $this->GetIdJurusan($data->jurusan);
                    if (!$idJurusan) {
                        continue;
                    }
                    
                    $dataToInsert[] = [
                        'nim' => $data->nim,
                        'nama' => $data->nama,
                        'tempat_lahir' => $data->tempat_lahir,
                        'jenis_kelamin' => $data->jenis_kelamin,
                        'alamat' => $data->alamat,
                        'jurusan' => $idJurusan,
                        'tahun_masuk' => $data->tahun_masuk,
                        'provinsi' => $data->provinsi,
                        'kabupaten_kota' => $data->kabupaten_kota,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                    $processedIds[] = $data->id;
                }
                
                if (!empty($dataToInsert)) {
                    Mahasiswa::insert($dataToInsert);
                    DataPendingModel::whereIn('id', $processedIds)->delete();
                }
            });
    
            return back()->with('alert', [
                'title' => 'Berhasil',
                'message' => 'Data Mahasiswa Berhasil Di Update',
                'type' => 'success'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
            ]);
        }
    }

    public function DeleteNewData()
    {
        DataPendingModel::truncate();
        // DataPendingModel::query()->delete();
        return back()->with('alert', [
            'title' => 'Berhasil',
            'message' => 'Data Mahasiswa Berhasil Di Hapus Semua',
            'type' => 'success'
        ]);
    }

    private function GetIdJurusan($jurusan)
    {
        $jurusan = str_replace(' ', '_', strtolower($jurusan));
        return Jurusan::where('name', $jurusan)->pluck('id')->first();
    }
    
    public function DeleteOnceNewData(Request $request)
    {
        $data = DataPendingModel::findOrFail($request->id);
        $nama = $data->name;
        $data->delete();
        return back()->with('alert', [
            'title' => 'Berhasil',
            'message' => 'Berhasil Menghapus Data '.$nama, 
            'type' => 'success'
        ]);
    }
}
