@extends('layout')

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
                                Pertumbuhan Mahasiswa</h5>
                        </div>
                    </div>
                    <div class="container">
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
                    </div>
                    <!-- Tambahkan div wrapper untuk mengontrol ukuran canvas -->
                    <div class="d-flex justify-content-center" style="position: relative; margin: 0; height: 75%; margin: auto;">
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
                            <h5 class="card-title fw-semibold" style="text-shadow: 0px 0px 30px rgba(0, 128, 0, 0.6);">Pertumbuhan Mahasiswa</h5>
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
                                Pertumbuhan Mahasiswa</h5>
                        </div>
                    </div>
                    <div class="container">
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
                    </div>
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script>
<script>
    Chart.register(ChartDataLabels);

    const tahun = ['2020', '2021', '2022', '2023', '2024'];
    const fullData = {
        '2020': {
            value: 65,
            color: 'rgba(255, 99, 132, 0.8)',
            border: 'rgba(255, 99, 132, 1)'
        },
        '2021': {
            value: 78,
            color: 'rgba(54, 162, 235, 0.8)',
            border: 'rgba(54, 162, 235, 1)'
        },
        '2022': {
            value: 82,
            color: 'rgba(255, 206, 86, 0.8)',
            border: 'rgba(255, 206, 86, 1)'
        },
        '2023': {
            value: 95,
            color: 'rgba(75, 192, 192, 0.8)',
            border: 'rgba(75, 192, 192, 1)'
        },
        '2024': {
            value: 88,
            color: 'rgba(153, 102, 255, 0.8)',
            border: 'rgba(153, 102, 255, 1)'
        }
    };

    let myChart;

    function updateChart() {
        const selectedYears = Object.keys(fullData).filter(year =>
            document.getElementById(year).checked
        );

        const data = {
            labels: selectedYears,
            datasets: [{
                data: selectedYears.map(year => fullData[year].value),
                backgroundColor: selectedYears.map(year => fullData[year].color),
                borderColor: selectedYears.map(year => fullData[year].border),
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

    // Event listener untuk select
    // document.querySelector('.form-select').addEventListener('change', function (e) {
    //     const selectedYear = e.target.value; // Tahun yang dipilih
    //     const yearIndex = originalData.labels.indexOf(selectedYear); // Indeks tahun

    //     if (selectedYear === "") {
    //         // Reset ke data asli jika memilih "All"
    //         myBarChart.data.labels = [...originalData.labels]; // Clone labels
    //         myBarChart.data.datasets.forEach((dataset, index) => {
    //             dataset.data = [...originalData.datasets[index].data]; // Clone data
    //         });
    //     } else if (yearIndex !== -1) {
    //         // Jika tahun valid
    //         myBarChart.data.labels = [selectedYear, selectedYear]; // Duplikasi label untuk membentuk garis
    //         myBarChart.data.datasets.forEach((dataset, index) => {
    //             const value = originalData.datasets[index].data[yearIndex];
    //             dataset.data = [value, value]; // Duplikasi nilai data
    //         });
    //     }

    //     // Update chart
    //     myBarChart.update(); // Refresh grafik
    // });
































    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('rzw-chart').getContext('2d');
        const originalData = {
            labels: ['2019', '2020', '2021', '2022', '2023', '2024'],
            datasets: [
                {
                    label: 'Hubungan Internasional',
                    data: [41, 38, 65, 79, 57, 69],
                    fill: false,
                    borderColor: '#0096eb',
                    tension: 0.1
                },
                {
                    label: 'Ilmu Komunikasi',
                    data: [277, 294, 204, 277, 223, 186],
                    fill: false,
                    borderColor: '#ff0000',
                    tension: 0.1
                },
                {
                    label: 'Ilmu Pemerintahan',
                    data: [27, 23, 21, 11, 12, 14],
                    fill: false,
                    borderColor: '#ffb600',
                    tension: 0.1
                },
            ]
        };

        // Get select elements
        const startYear = document.getElementById('startYear');
        const endYear = document.getElementById('endYear');

        // Populate select options from originalData.labels
        originalData.labels.forEach(year => {
            startYear.add(new Option(year, year));
            endYear.add(new Option(year, year));
        });

        // Inisialisasi chart
        const myBarChart = new Chart(ctx, {
            type: 'line',
            data: JSON.parse(JSON.stringify(originalData)), // Deep clone originalData
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });

        // Function to update chart based on year range
        function updateChart() {
            if (!startYear.value || !endYear.value) return;
            
            const startIndex = originalData.labels.indexOf(startYear.value);
            const endIndex = originalData.labels.indexOf(endYear.value);

            if (startIndex > endIndex) {
                alert('Tahun awal harus lebih kecil dari tahun akhir');
                return;
            }

            // Get data for selected range
            const selectedLabels = originalData.labels.slice(startIndex, endIndex + 1);
            const selectedData = originalData.datasets.map(dataset => ({
                ...dataset,
                data: dataset.data.slice(startIndex, endIndex + 1)
            }));

            // Update chart
            myBarChart.data.labels = selectedLabels;
            myBarChart.data.datasets = selectedData;
            myBarChart.update();
        }

        // Event listeners for select inputs
        startYear.addEventListener('change', updateChart);
        endYear.addEventListener('change', updateChart);

        // Reset button
        document.getElementById('resetBtn').addEventListener('click', function() {
            startYear.value = '';
            endYear.value = '';
            
            // Reset chart ke data asli
            myBarChart.data.labels = [...originalData.labels];
            myBarChart.data.datasets = originalData.datasets.map(dataset => ({
                ...dataset,
                data: [...dataset.data]
            }));
            myBarChart.update();
        });
    });
</script>
@endsection