@extends('layout')

@section('title', 'Ilmu Pemerintahan')

@section('css')
<style>
    .chart-container {
        width: 800px;
        height: 400px;
        margin: 20px auto;
    }

    .controls {
        width: 100%;
        margin: 20px auto;
        padding: 15px;
        background-color: #f5f5f5;
        border-radius: 8px;
    }

    .checkbox-group {
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    input[type="checkbox"] {
        cursor: pointer;
    }

    label {
        cursor: pointer;
        user-select: none;
    }
</style>
<style>
    #map {
        height: 500px;
        width: 100%;
        border: 1px solid #ccc;
    }

    .legend {
        line-height: 25px;
        color: #333;
    }
</style>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endsection

@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-lg-6 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body shadow">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold" style="text-shadow: 0px 0px 30px rgba(0, 128, 0, 0.6);">
                                Jenis Kelamin</h5>
                        </div>
                    </div>
                    <div class="container">
                        <div class="controls">
                            <div class="checkbox-group" id="range_tahun">
                            </div>
                        </div>
                    </div>
                    <!-- Tambahkan div wrapper untuk mengontrol ukuran canvas -->
                    <div class="d-flex justify-content-center"
                        style="position: relative; margin: 0; height: 75%; margin: auto;">
                        <canvas id="rzw-piechart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body shadow">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold" style="text-shadow: 0px 0px 30px rgba(0, 128, 0, 0.6);">
                                Mahasiswa Berdasarkan Pertumbuhan</h5>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="d-flex gap-2 align-items-center">
                                <div>
                                    <label class="small text-muted">Dari:</label>
                                    <select class="form-select" id="startYear">
                                        <option value="">Pilih Tahun</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="small text-muted">Sampai:</label>
                                    <select class="form-select" id="endYear">
                                        <option value="">Pilih Tahun</option>
                                    </select>
                                </div>
                                <button class="btn btn-secondary mt-4" id="resetBtn">Reset</button>
                            </div>
                            <small class="text-muted mt-1">Range tahun: 2019 - 2024</small>
                        </div>
                    </div>
                    <canvas id="rzw-chart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-12 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body shadow">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold" style="text-shadow: 0px 0px 30px rgba(0, 128, 0, 0.6);">
                                Mahasiswa Berdasarkan Wilayah</h5>
                        </div>
                    </div>
                    {{-- <div class="container">
                        <div class="controls">
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <input type="checkbox" id="2020" value="2020" checked>
                                    <label for="2020">2020</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="2021" value="2021" checked>
                                    <label for="2021">2021</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="2022" value="2022" checked>
                                    <label for="2022">2022</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="2023" value="2023" checked>
                                    <label for="2023">2023</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" id="2024" value="2024" checked>
                                    <label for="2024">2024</label>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="container mt-4">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @dd($mahasiswa_per_tahun) --}}

@section('script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js">
</script>

<script>
    Chart.register(ChartDataLabels);

    var JurusanList = {!! json_encode($jurusan) !!};
    var range_tahun = document.querySelector('#range_tahun')
    JurusanList.forEach(element => {
        range_tahun.innerHTML += `
            <div class="checkbox-item">
                <input type="checkbox" id="${element.label}" value="${element.label}" checked>
                <label for="${element.name}">${element.label}</label>
            </div>
        `;
    });
    
    const fullData = {!! json_encode($PieChart) !!};
    let myChart;

    console.log(`Jurusan : ${JSON.stringify(fullData)}`)

    function updateChart() {
        const selectedJurusan = Object.keys(fullData).filter(jurusan =>
            document.getElementById(jurusan).checked
        );

        const data = {
            labels: selectedJurusan,
            datasets: [{
                data: selectedJurusan.map(jurusan => fullData[jurusan].value),
                backgroundColor: selectedJurusan.map(jurusan => fullData[jurusan].color),
                borderColor: selectedJurusan.map(jurusan => fullData[jurusan].border),
                borderWidth: 1
            }]
        };

        if (myChart) {
            myChart.destroy();
        }

        const ctx = document.getElementById('rzw-piechart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {
                            const datapoints = ctx.chart.data.datasets[0].data;
                            const total = datapoints.reduce((total, datapoint) => total + datapoint, 0);
                            const percentage = (value / total * 100).toFixed(1);
                            return `${ctx.chart.data.labels[ctx.dataIndex]}\n(${percentage}%)`;
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 12
                        }
                    },
                    legend: {
                        display: true,
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    document.querySelectorAll('.checkbox-item input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateChart);
    });
    updateChart();



























    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('rzw-chart').getContext('2d');
        const originalData = {
            labels: {!! json_encode($tahun_range) !!},
            datasets: {!! json_encode($LineChart) !!}
        };

        const startYear = document.getElementById('startYear');
        const endYear = document.getElementById('endYear');

        originalData.labels.forEach(year => {
            startYear.add(new Option(year, year));
            endYear.add(new Option(year, year));
        });

        const myBarChart = new Chart(ctx, {
            type: 'line',
            data: JSON.parse(JSON.stringify(originalData)),
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                },
                plugins: {
                    annotation: {
                        annotations: {
                            arrow: {
                                type: 'line',
                                borderColor: '#0096eb',
                                borderWidth: 2,
                                label: {
                                    enabled: true,
                                    content: '↑',
                                    position: 'end'
                                },
                                scaleID: 'y',
                                value: 'end'
                            }
                        }
                    }
                }
            }
        });

        function updateChart() {
            if (!startYear.value || !endYear.value) return;
            const start = parseInt(startYear.value);
            const end = parseInt(endYear.value);

            if (start > end) {
                Swal.fire({
                    title: "Opsss!",
                    text: "Tahun  Awal Harus Lebih Kecil Dari Tahun Akhir",
                    icon: "warning"
                });
                return 
            }

            const startIndex = originalData.labels.findIndex(year => parseInt(year) === start);
            const endIndex = originalData.labels.findIndex(year => parseInt(year) === end);
            const selectedLabels = originalData.labels.slice(startIndex, endIndex + 1);
            const selectedData = originalData.datasets.map(dataset => ({
                ...dataset,
                data: dataset.data.slice(startIndex, endIndex + 1)
            }));
            myBarChart.data.labels = selectedLabels;
            myBarChart.data.datasets = selectedData;
            myBarChart.update();
        }

        startYear.addEventListener('change', updateChart);
        endYear.addEventListener('change', updateChart);

        document.getElementById('resetBtn').addEventListener('click', function() {
            startYear.value = '';
            endYear.value = '';
            
            myBarChart.data.labels = [...originalData.labels];
            myBarChart.data.datasets = originalData.datasets.map(dataset => ({
                ...dataset,
                data: [...dataset.data]
            }));
            myBarChart.update();
        });
    });
</script>













<script>
    var southWest = L.latLng(-11.0, 94.0);
    var northEast = L.latLng(6.0, 141.0);
    var bounds = L.latLngBounds(southWest, northEast);
    var map = L.map('map', {
        maxBounds: bounds,
        maxBoundsViscosity: 1.0,
        minZoom: 4 
    });

    var MapsLayer = L.tileLayer('https://tiles.stadiamaps.com/tiles/stamen_toner_background/{z}/{x}/{y}{r}.{ext}', {
        minZoom: 0,
        maxZoom: 20,
        attribution: '&copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://www.stamen.com/" target="_blank">Stamen Design</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        ext: 'png'
    });

    MapsLayer.addTo(map);

    var CartoDB_Positron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 20
    });

    CartoDB_Positron.addTo(map);

    var dataSiswa = {!! json_encode($Geo) !!};

    var provinsi = {
        "ACEH": [4.6951, 96.7494],
        "SUMATERA UTARA": [2.1154, 99.5451],
        "SUMATERA BARAT": [-0.7397, 100.8000],
        "RIAU": [0.2933, 101.7068],
        "JAMBI": [-1.6101, 103.6131],
        "SUMATERA SELATAN": [-3.3194, 104.9144],
        "BENGKULU": [-3.7928, 102.2608],
        "LAMPUNG": [-4.5585, 105.4068],
        "KEPULAUAN BANGKA BELITUNG": [-2.7411, 106.4406],
        "KEPULAUAN RIAU": [3.9456, 108.1428],
        "DKI JAKARTA": [-6.2088, 106.8456],
        "JAWA BARAT": [-6.8897, 107.6405],
        "JAWA TENGAH": [-7.1510, 110.1403],
        "DI YOGYAKARTA": [-7.8753, 110.4262],
        "JAWA TIMUR": [-7.5360, 112.2384],
        "BANTEN": [-6.4058, 106.0640],
        "BALI": [-8.3405, 115.0920],
        "NUSA TENGGARA BARAT": [-8.6529, 117.3616],
        "NUSA TENGGARA TIMUR": [-8.6574, 121.0794],
        "KALIMANTAN BARAT": [-0.2787, 111.4752],
        "KALIMANTAN TENGAH": [-1.6813, 113.3823],
        "KALIMANTAN SELATAN": [-3.0926, 115.2838],
        "KALIMANTAN TIMUR": [0.5386, 116.4194],
        "KALIMANTAN UTARA": [3.0731, 116.0413],
        "SULAWESI UTARA": [0.6246, 123.9750],
        "SULAWESI TENGAH": [-1.4300, 121.4456],
        "SULAWESI SELATAN": [-3.6687, 119.9740],
        "SULAWESI TENGGARA": [-4.1449, 122.1746],
        "GORONTALO": [0.6999, 122.4467],
        "SULAWESI BARAT": [-2.8441, 119.2321],
        "MALUKU": [-3.2385, 130.1453],
        "MALUKU UTARA": [1.5709, 127.8087],
        "PAPUA": [-4.2699, 138.0804],
        "PAPUA BARAT": [-1.3361, 133.1747],
        "PAPUA SELATAN": [-7.6145, 139.9520],
        "PAPUA TENGAH": [-3.7792, 136.5068],
        "PAPUA PEGUNUNGAN": [-4.5286, 140.5132]
    };

    function getColor(provinsiName) {
        let provinsi = provinsiName.toUpperCase();
        var mahasiswa = dataSiswa[provinsi];
        if (!mahasiswa) return "#fff";
        
        return mahasiswa > 250 ? '#d94801' :
        mahasiswa > 200 ? '#f16913' :
        mahasiswa > 150 ? '#fd8d3c' :
        mahasiswa > 100 ? '#fdae6b' :
        mahasiswa > 50 ? '#fdd0a2' : 
                              '#fee6ce';
    }

    function CariProvinsi() {
        let maxSiswa = 0;
        let provinsiMax = null;
        
        Object.entries(dataSiswa).forEach(([provinsi, mahasiswa]) => {
            if (mahasiswa > maxSiswa) {
                maxSiswa = mahasiswa;
                provinsiMax = provinsi;
            }
        });
        
        return provinsiMax;
    }

    fetch('https://raw.githubusercontent.com/superpikar/indonesia-geojson/master/indonesia.geojson')
        .then(response => response.json())
        .then(data => {
            const geoJsonLayer = L.geoJSON(data, {
                style: function(data) {
                    return {
                        fillColor: getColor(data.properties.state),
                        color: "transparent",
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.7
                    };
                },
                onEachFeature: function(data, layer) {
                    const provinsiName = data.properties.state.toUpperCase();
                    const studentCount = dataSiswa[provinsiName];
                    layer.bindPopup(`
                        <strong>${data.properties.state}</strong><br>
                        ${studentCount ? 
                            `Jumlah Siswa: ${studentCount.toLocaleString()}` : 
                            'Data tidak tersedia'}
                    `);

                    if (provinsi[provinsiName]) {
                        layer.feature.properties.center = provinsi[provinsiName];
                    }
                }
            }).addTo(map);

            const ProvinsiMahasiswa = CariProvinsi();
            if (ProvinsiMahasiswa && provinsi[ProvinsiMahasiswa]) {
                const coordinates = provinsi[ProvinsiMahasiswa];
                map.setView(coordinates, 7);
            }
        });

    var legend = L.control({position: 'bottomright'});
    legend.onAdd = function(map) {
        var div = L.DomUtil.create('div', 'info legend');
        var grades = [250, 200, 150, 100, 50, 5];
        var colors = ['#d94801', '#f16913', '#fd8d3c', '#fdae6b', '#fdd0a2', '#fee6ce'];
        
        div.innerHTML = '<div style="background: white; padding: 10px; border-radius: 5px; border: 1px solid #ccc;">' +
                       '<strong>Jumlah Siswa</strong><br>';
        
        for (var i = 0; i < grades.length; i++) {
            div.innerHTML +=
                '<i style="background: ' + colors[i] + '; display: inline-block; width: 20px; height: 20px; margin-right: 5px;"></i> ' +
                (grades[i] ? '> ' + grades[i].toLocaleString() : '< 100,000') + '<br>';
        }
        
        div.innerHTML += '</div>';
        return div;
    };
    legend.addTo(map);

    map.on('drag', function() {
        map.panInsideBounds(bounds, { animate: false });
    });
</script>
@endsection