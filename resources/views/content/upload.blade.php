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
    <div class="container mt-5">
        <!-- File Input Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Upload File</h5>
                        <form action="{{ url('upload') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group">
                                <input type="file" 
                                       name="data_mahasiswa" 
                                       class="form-control @error('data_mahasiswa') is-invalid @enderror" 
                                       id="data_mahasiswa">
                                <button class="btn btn-primary" type="submit">Upload</button>
                                @error('data_mahasiswa')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        @if ($FilePending->count() >= 1)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">File Status</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">File Name</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($FilePending as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->file_name }}</td>
                                            <td>{!! $item->status == 1 ? "<span class='badge bg-success'>Berhasil</span>" : "<span class='badge bg-danger'>Pending</span>" !!}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        @if ($DataPending->count() >= 1)
            <!-- Table Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Data Pending</h5>
                            <div class="row text-end pb-3">
                                <div class="col-12">
                                    <button class="btn btn-sm btn-primary" onclick="tambah_data()">Tambah Semua</button>
                                    <button class="btn btn-sm btn-danger" onclick="hapus_data()">Hapus Semua</button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">NIM</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Tempat Lahir</th>
                                            <th scope="col">Jenis Kelamin</th>
                                            <th scope="col">Alamat</th>
                                            <th scope="col">Jurusan</th>
                                            <th scope="col">Tahun Masuk</th>
                                            <th scope="col">Provinsi</th>
                                            <th scope="col">Kabupaten Kota</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($DataPending as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->nim }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->tempat_lahir }}</td>
                                                <td>{{ $item->jenis_kelamin }}</td>
                                                <td>{{ $item->alamat }}</td>
                                                <td>{{ $item->jurusan }}</td>
                                                <td>{{ $item->tahun_masuk }}</td>
                                                <td>{{ $item->provinsi }}</td>
                                                <td>{{ $item->kabupaten_kota }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger" onclick="hapus_data_by_id('{{ $item->id }}', '{{ $item->nama }}')">Hapus</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


@section('script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script>
<script>
    function tambah_data() {
        Swal.fire({
            title: "Kamu Yakin?",
            text: "Apakah Kamu Yakin Ingin Menambah Semua Data Ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya!"
        }).then((result) => {
            if (result.isConfirmed) {
                // fetch(`{{ url('InsertNewData') }}`, {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': `{{ csrf_token() }}`
                //     },
                //     body: JSON.stringify({})
                // })
                // .then(response => response.json())
                // .then(data => {
                //     if (data.success) {
                //         Swal.fire({
                //             title: "Berhasil",
                //             text: "Berhasil Menambah Data Mahasiswa",
                //             icon: "success"
                //         });
                //     } else {
                //         Swal.fire({
                //             title: "Gagal",
                //             text: "Hmmmm Ada Kesalahan System",
                //             icon: "error"
                //         });
                //     }
                // })
                // .catch(error => {
                //     Swal.fire({
                //             title: "Gagal",
                //             text: "Hmmmm Ada Kesalahan System",
                //             icon: "error"
                //         });
                // });


                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('InsertNewData') }}`;
                
                // Add CSRF token
                const csrfToken = `{{ csrf_token() }}`;
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }


    function hapus_data() {
        Swal.fire({
            title: "Kamu Yakin?",
            text: "Apakah Kamu Yakin Ingin Menghapus Semua Data Ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya!"
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('DeleteNewData') }}`;
                
                // Add CSRF token
                const csrfToken = `{{ csrf_token() }}`;
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function hapus_data_by_id(id, nama) {
        Swal.fire({
            title: "Kamu Yakin?",
            text: `Apakah Kamu Yakin Ingin Menghapus Data ${nama}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya!"
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ url('DeleteOnceNewData') }}`;
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = `{{ csrf_token() }}`;
                form.appendChild(csrfInput);
                
                // Add data input
                const dataInput = document.createElement('input');
                dataInput.type = 'hidden';
                dataInput.name = 'id';  // Nama field yang akan dikirim
                dataInput.value = id; // Value yang akan dikirim
                form.appendChild(dataInput);
                
                // Append form and submit
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection