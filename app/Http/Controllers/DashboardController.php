<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Dashboard";
        return view('content.dashboard',compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $prodi)
    {
        $datas = [
            'hubungan_ternasional' => [
                'title' => "Hubungan internasional",
                'data' => [
                    [
                        'label' => 'Hubungan Internasional',
                        'data' => [41, 38, 65, 79, 57, 69],
                        'fill' => false,
                        'borderColor' => '#0096eb',
                        'tension' => 0.1
                    ],
                ]
            ],
            'ilmu_komunikasi' => [
                'title' => "Ilmu Komunikasi",
                'data' => [
                    [
                        'label' => 'Ilmu Komunikasi', 
                        'data' => [277, 294, 204, 277, 223, 186],
                        'fill' => false,
                        'borderColor' => '#ff0000',
                        'tension' => 0.1
                    ],
                ]
            ],
            'ilmu_pemerintahan' => [
                'title' => "Ilmu Pemerintahan",
                'data' => [
                    [
                        'label' => 'Ilmu Pemerintahan',
                        'data' => [27, 23, 21, 11, 12, 14],
                        'fill' => false,
                        'borderColor' => '#ffb600',
                        'tension' => 0.1
                    ]
                ]
            ],

        ];

        if (!isset($datas[$prodi])) {
            return redirect('/')->with('alert', [
                'title' => 'Ops!',
                'message' => 'Data tidak ditemukan',
                'type' => 'warning'
            ]);
        }
        
        $title = $datas[$prodi]['title'];
        $datasets = $datas[$prodi]['data'];
        return view('content.prodi',compact('title', 'datasets'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
