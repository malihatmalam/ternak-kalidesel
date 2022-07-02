@extends('layout_master.master')

@section('content')
    <!-- INPUTS -->
    <div class="main">
        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="container-fluid">
                @if (Session::has('pesan'))
                    <div class="alert alert-success">{{ Session::get('pesan') }}</div>
                @endif
                <h2 class="page-title">Konsultasi Penyakit Hewan Ternak</h2>
                <div class="row">

                    <div class="panel panel-headline">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ringkasan Informasi Mengenai Konsultasi Penyakit Ternak</h3>
                            <p class="panel-subtitle">Hari ini</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="metric">
                                        <span class="icon"><i class="lnr lnr-heart"></i></span>
                                        <p>
                                            <span class="number">{{ $count_complaint }}</span>
                                            <span class="title">Total Konsultasi</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="metric">
                                        <span class="icon"><i class="lnr lnr-heart"></i></span>
                                        <p>
                                            <span class="number">{{ $count_diagnosis }} </span>
                                            <span class="title">Konsultasi Telah Dijawab</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="metric">
                                        <span class="icon"><i class="lnr lnr-heart"></i></span>
                                        <p>
                                            <span class="number">{{ $count_diagnosis_waiting }} </span>
                                            <span class="title">Konsultasi yang Masih Menunggu</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            {{-- <h3>Pencarian</h3>
                            <form action="{{ route('livestock.search') }}" method="get">
                                @csrf
                                <input type="text" name="kata" class="form-control" placeholder="Cari..."
                                    style="width:30%; display:inline; margin-top:10px; margin-bottom:10px; float:left;">
                            </form> --}}
                        </div>
                    </div>
                </div>


                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Konsultasi Penyaki Ternak yang <strong>Menunggu</strong></h3>
                        {{-- <p class="panel-subtitle">Hari ini</p> --}}
                    </div>
                    <div class="panel-body">
                        <table class="table data-table stripe hover nowrap" id="complaintTable">
                            <thead>
                                <tr>
                                    <th class="table-plus">Kode Konsultasi</th>
                                    <th> Gejala / Keluhan </th>
                                    <th> Tanggal Buat </th>
                                    <th class="datatable-nosort"> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($complaints as $complaint)
                                    <tr>
                                        <td class="table-plus">{{ $complaint->code }}</td>
                                        <td>{{ Str::limit($complaint->complaint, 200, ' ...') }}</td>
                                        <td>{{ $complaint->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('diagnosis.make', $complaint->code) }}"
                                                class="btn btn-primary">
                                                Buat Diagnosis
                                            </a>
                                            {{-- <form action="{{ route('livestock.mati', $animal->id) }}" method="POST">
                                                @csrf
                                                <!-- <a href="{{ route('livestock.edit', $animal->id) }}" class="btn btn-warning">
                                                                                    Update
                                                                                </a> -->
                                                <button type="submit" class="btn btn-danger"
                                                    onClick="return confirm('Apakah anda yakin ?') ">
                                                    Mati
                                                </button>
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            {{-- <div class="kiri"><strong>Jumlah Hewan : {{ $jumlah_hewan }}</strong></div> --}}
                            {{-- <div class="kanan">{{ $livestock->links() }}</div> --}}
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Konsultasi Penyakit Ternak yang <strong>Telah Dijawab</strong></h3>
                        {{-- <p class="panel-subtitle">Hari ini</p> --}}
                    </div>
                    <div class="panel-body">
                        <table class="table data-table stripe hover nowrap" id="diagnosisTable">
                            <thead>
                                <tr>
                                    <th class="table-plus">Kode Konsultasi</th>
                                    <th> Gejala / Keluhan </th>
                                    <th> Tanggal Buat </th>
                                    <th class="datatable-nosort"> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($diagnosis as $diagnosa)
                                    <tr>
                                        <td class="table-plus">{{ $diagnosa->code }}</td>
                                        <td>{{ Str::limit($diagnosa->complaint, 200, ' ...') }}</td>
                                        <td>{{ $diagnosa->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('diagnosis.detail', $diagnosa->code) }}"
                                                class="btn btn-info">
                                                Detail
                                            </a>
                                            {{-- <form action="{{ route('livestock.mati', $animal->id) }}" method="POST">
                                                @csrf
                                                <!-- <a href="{{ route('livestock.edit', $animal->id) }}" class="btn btn-warning">
                                                                                    Update
                                                                                </a> -->
                                                <button type="submit" class="btn btn-danger"
                                                    onClick="return confirm('Apakah anda yakin ?') ">
                                                    Mati
                                                </button>
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            {{-- <div class="kiri"><strong>Jumlah Hewan : {{ $jumlah_hewan }}</strong></div> --}}
                            {{-- <div class="kanan">{{ $livestock->links() }}</div> --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
@endsection

@section('js')
    <script>
        $('.data-table').DataTable({
            scrollCollapse: true,
            autoWidth: true,
            responsive: true,
            columnDefs: [{
                targets: "datatable-nosort",
                orderable: false,
            }],
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "language": {
                "info": "_START_-_END_ of _TOTAL_ entries",
                searchPlaceholder: "Cari hewan",
                // paginate: {
                //     next: '<i class="ion-chevron-right"></i>',
                //     previous: '<i class="ion-chevron-left"></i>'
                // }
            },
        });
    </script>
@endsection
