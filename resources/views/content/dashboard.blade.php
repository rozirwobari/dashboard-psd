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
                        <div>
                            <select class="form-select">
                                <option value="">All</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                            </select>
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
    const ctx = document.getElementById('rzw-chart').getContext('2d');
    const originalData = {
        labels: ['2019', '2020', '2021', '2022', '2023', '2024'], // Label untuk sumbu X
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

    // Event listener untuk select
    document.querySelector('.form-select').addEventListener('change', function (e) {
        const selectedYear = e.target.value; // Tahun yang dipilih
        const yearIndex = originalData.labels.indexOf(selectedYear); // Indeks tahun

        if (selectedYear === "") {
            // Reset ke data asli jika memilih "All"
            myBarChart.data.labels = [...originalData.labels]; // Clone labels
            myBarChart.data.datasets.forEach((dataset, index) => {
                dataset.data = [...originalData.datasets[index].data]; // Clone data
            });
        } else if (yearIndex !== -1) {
            // Jika tahun valid
            myBarChart.data.labels = [selectedYear, selectedYear]; // Duplikasi label untuk membentuk garis
            myBarChart.data.datasets.forEach((dataset, index) => {
                const value = originalData.datasets[index].data[yearIndex];
                dataset.data = [value, value]; // Duplikasi nilai data
            });
        }

        // Update chart
        myBarChart.update(); // Refresh grafik
    });
</script>
@endsection
