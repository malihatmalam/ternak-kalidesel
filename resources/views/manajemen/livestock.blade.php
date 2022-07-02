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
                <h2 class="page-title">Daftar Hewan Ternak <b> (Semuanya) </b></h2>
                <div class="row">

                    <div class="panel panel-headline">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ringkasan Informasi Mengenai Stok Ternak</h3>
                            <p class="panel-subtitle">Hari ini</p>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="metric">
                                        <span class="icon"><i class="fa fa-bar-chart"></i></span>
                                        <p>
                                            <span class="number">{{ $jumlah_stock }} Ekor</span>
                                            <span class="title">Stok Hewan</span>
                                        </p>
                                        <br>
                                        <br>
                                        <div>
                                            <h3 class="panel-title">Tambah Data Hewan Ternak</h3>
                                            <br>
                                            <form action="{{ route('livestock.add') }}" method="GET">
                                                @csrf
                                                <button type="submit" class="btn btn-primary">Tambah</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="metric">
                                        <span class="icon"><i class="fa fa-bar-chart"></i></span>
                                        <p>
                                            <span class="number">{{ $jumlah_dibeli }} Ekor</span>
                                            <span class="title">Hewan Yang Sudah Dibeli</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="metric">
                                        <span class="icon"><i class="fa fa-bar-chart"></i></span>
                                        <p>
                                            <span class="number">{{ $jumlah_mati }} Ekor</span>
                                            <span class="title">Hewan Yang Sudah Mati</span>
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
                    <div class="panel-body">
                        <table class="table data-table stripe hover nowrap" id="livestockTable">
                            <thead>
                                <tr>
                                    <th class="table-plus">Kode Hewan</th>
                                    <th> Jenis </th>
                                    <th> Kode Kandang </th>
                                    <th> Tipe </th>
                                    <th> Berat </th>
                                    <th> Harga </th>
                                    <th> Kondisi </th> {{-- Sakit/Sehat --}}
                                    <th> Status </th>
                                    <th class="datatable-nosort"> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($livestock as $animal)
                                    <tr>
                                        <td class="table-plus">{{ $animal->kode }}</td>
                                        <td>
                                            @if ($animal->jenis == 'cow')
                                                <img src="{{ asset('admin/assets/images/Sapi.jpg') }}"
                                                    style="width: 100px">  <strong> : Sapi</strong>
                                            @endif
                                            @if ($animal->jenis == 'goat')
                                                <img src="{{ asset('admin/assets/images/Kambing.jpg') }}"
                                                    style="width: 100px">  <strong> : Kambing</strong>
                                            @endif
                                            @if ($animal->jenis == 'sheep')
                                                <img src="{{ asset('admin/assets/images/Domba.jpg') }}"
                                                    style="width: 100px">  <strong> : Domba</strong>
                                            @endif
                                        </td>
                                        <td>{{ $animal->kode_kandang }}</td>
                                        <td>{{ $animal->type }}</td>
                                        <td>{{ $animal->berat }} Kg</td>
                                        <td>Rp. {{ number_format($animal->harga) }}</td>
                                        <td>
                                            @if ( $animal->condition == 'sehat')
                                                <span class="label label-success">Sehat</span>
                                            @endif
                                            @if ( $animal->condition == 'sakit')
                                                <span class="label label-warning">Sakit</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($animal->status == 'Sudah dibeli')
                                                <span class="label label-success">{{ $animal->status }}</span>
                                            @endif
                                            @if ($animal->status == 'Belum dibeli')
                                                <span class="label label-warning">{{ $animal->status }}</span>
                                            @endif
                                            @if ($animal->status == 'Mati')
                                                <span class="label label-danger">{{ $animal->status }}</span>
                                            @endif
                                        <td>
                                            <form action="{{ route('livestock.mati', $animal->id) }}" method="POST">
                                                @csrf
                                                <a href="{{ route('livestock.detail', $animal->id) }}"
                                                    class="btn btn-primary">
                                                    Detail
                                                </a>
                                                <!-- <a href="{{ route('livestock.edit', $animal->id) }}" class="btn btn-warning">
                                                                                    Update
                                                                                </a> -->
                                                <button type="submit" class="btn btn-danger"
                                                    onClick="return confirm('Apakah anda yakin ?') ">
                                                    Mati
                                                </button>
                                            </form>
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
