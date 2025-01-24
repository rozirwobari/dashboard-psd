@extends('layout')

@section('content')
<div class="container pt-5">
    <div class="row pt-5">
        <div class="col-lg-12 d-flex align-items-strech">
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
    </div>
</div>
@endsection


@section('script')
<script>
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
                }
            }
        });

        function updateChart() {
            if (!startYear.value || !endYear.value) return;
            
            const startIndex = originalData.labels.indexOf(startYear.value);
            const endIndex = originalData.labels.indexOf(endYear.value);

            if (startIndex > endIndex) {
                alert('Tahun awal harus lebih kecil dari tahun akhir');
                return;
            }

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
@endsection
