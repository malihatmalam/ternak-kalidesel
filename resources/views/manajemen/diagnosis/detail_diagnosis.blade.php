@extends('layout_master.master')

@section('content')

    <!-- Select 2 css -->
    <link rel="stylesheet" href="{{ asset('admin\assets\plugins\select2\css\select2.min.css') }}">

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}

    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

    <link href="{{ asset('admin\assets\plugins\limitless\css\custom-limitless-components.css') }}" rel="stylesheet"
        type="text/css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/css/lightbox.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>

    <style>
        .thumb {
            margin: 10px 5px 0 0;
            width: 300px;
            height: 300px;
        }

        .guide-icon {
            border: 2px solid purple;
            border-radius: 5px;
            padding: 4px;
        }

        .guide-title {
            margin: 8px;
        }
    </style>
    <style type="text/css">
        #mymap {
            /* border:1px solid red; */
            width: 800px;
            height: 250px;
        }

        .gallery {
            display: inline-block;
            margin-top: 20px;
        }

        .close-icon {
            border-radius: 50%;
            position: absolute;
            height: 30px;
            width: 30px;
            right: 4px;
            top: -20px;
            bottom: 10px;
            padding: 2px 2px;
        }
    </style>

    <!-- INPUTS -->
    <div class="main">
        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="container-fluid">

                <h2 class="page-title"><b> Detail Konsultasi Penyakit Ternak </b></h2>
                @if (Session::has('pesan'))
                    <div class="alert alert-success">{{ Session::get('pesan') }}</div>
                @endif
                <div class="panel">

                    <div class="panel-heading">
                        <h2 class="panel-title">Data Penyakit Ternak : <strong>{{ $diagnosis->code }}</strong> </h2>
                    </div>
                    <div class="panel-body">
                        <!-- Gejala penyakit hewan -->
                        <label for="keluhan-hewan" class="col-sm-3 col-form-label"> Gejala Penyakit </label>
                        <textarea class="form-control" placeholder="Gejala/keluhan dari hewan ternak..." name="complaint" rows="4"
                            disabled>{{ $diagnosis->complaint }}</textarea>
                        <br>

                        @if ($diagnosis->images->count())
                            <div class="row photos">
                                @foreach ($diagnosis->images as $image)
                                    <div class="col-sm-6 col-md-4 col-lg-3 item  mt-3">
                                        <a href="{{ asset('Diagnosis/' . $image->image) }}" data-lightbox="photos">
                                            <img class="img-thumbnail" height="200" width="200"
                                                src="{{ asset('Diagnosis/' . $image->image) }}"
                                                style="margin-bottom: 10px">
                                        </a>
                                        {{-- <a href="" class="btn btn-danger">Hapus</a> --}}
                                    </div>
                                @endforeach
                            </div>
                        @endif


                        <hr>

                        @if (auth()->user()->role == 1)
                            <div>
                                <h3 class="panel-title">Tambah Data Hewan Ternak yang Terjangkit</h3>
                                <br>
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#livestock">
                                    Tambah Hewan Terjangkit
                                </button>
                            </div>
                        @endif

                        <br>
                        <table class="table data-table stripe hover nowrap" id="diagnosisTable">
                            <thead>
                                <tr>
                                    <th class="table-plus">Kode Hewan</th>
                                    <th> Jenis </th>
                                    <th> Kode Kandang </th>
                                    <th> Status </th>
                                    <th class="datatable-nosort"> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($livestocks as $animal)
                                    <tr>
                                        <td class="table-plus">{{ $animal->livestock->kode }}</td>
                                        <td>
                                            @if ($animal->livestock->jenis == 'cow')
                                                <img src="{{ asset('admin/assets/images/Sapi.jpg') }}"
                                                    style="width: 100px" alt="sapi"> <strong> : Sapi</strong>
                                            @endif
                                            @if ($animal->livestock->jenis == 'goat')
                                                <img src="{{ asset('admin/assets/images/Kambing.jpg') }}"
                                                    style="width: 100px" alt="kambing"> <strong> : Kambing</strong>
                                            @endif
                                            @if ($animal->livestock->jenis == 'sheep')
                                                <img src="{{ asset('admin/assets/images/Domba.jpg') }}"
                                                    style="width: 100px" alt="domba"> <strong> : Domba</strong>
                                            @endif
                                        </td>
                                        <td>{{ $animal->livestock->kode_kandang }}</td>
                                        <td>
                                            @if ($animal->status == 'sembuh')
                                                <span class="label label-success">{{ $animal->status }}</span>
                                            @endif
                                            @if ($animal->status == 'terjangkit')
                                                <span class="label label-warning">{{ $animal->status }}</span>
                                            @endif
                                        <td>
                                            <form action="{{ route('diagnosis.livestock.destroy', $animal->id) }}" method="POST">
                                                @csrf
                                                @if ($animal->status == 'sembuh')
                                                    <a href="{{ route('diagnosis.livestock.change-status', [$animal->id, 'terjangkit']) }}"
                                                        class="btn btn-warning">
                                                        Terjangkit
                                                    </a>
                                                @endif
                                                @if ($animal->status == 'terjangkit')
                                                    <a href="{{ route('diagnosis.livestock.change-status', [$animal->id, 'sembuh']) }}"
                                                        class="btn btn-success">
                                                        Sembuh
                                                    </a>
                                                @endif
                                                <button type="submit" class="btn btn-danger"
                                                    onClick="return confirm('Apakah anda yakin ?') ">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>



                    </div>
                </div>

                <div class="panel">

                    <div class="panel-heading">
                        <h2 class="panel-title">Diagnosis dan Perawatan Penyakit Ternak</h2>
                    </div>
                    <div class="panel-body">

                        <!-- Diagnosis penyakit hewan -->
                        <label for="diagnosa-hewan" class="col-sm-3 col-form-label"> Diagnosis Penyakit </label>
                        <br>
                        <span class="form-control">{{ $diagnosis->diagnosis }}</span>
                        <br>

                        <!-- Treatment hewan -->
                        <label for="treatment-hewan" class="col-sm-3 col-form-label"> Perawatan/pengobatan </label>
                        <br>
                        <span class="form-control">{{ $diagnosis->treatment }}</span>
                        <br>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="livestock" tabindex="-1" role="dialog" aria-labelledby="livestockLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form action="{{ route('diagnosis.livestock.add', $diagnosis->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Tambah Hewan yang Terjangkit</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">

                                    <label for="diagnosa-hewan" class="col-sm-4 col-form-label"> Tambah Hewan :
                                    </label>
                                    <br>
                                    <select class="form-control col-sm-12" name="livestock" required>
                                        <option value="">Pilih Hewan : <strong>Jenis _ Kode Hewan _ Kode Kandang</strong> </option>
                                        @foreach ($getLivestocks as $getAnimal)
                                        <option value="{{ $getAnimal->id }}">
                                            @if ($getAnimal->jenis == 'cow')
                                                Sapi 
                                            @endif
                                            @if ($getAnimal->jenis == 'goat')
                                                Kambing 
                                            @endif
                                            @if ($getAnimal->jenis == 'sheep')
                                                Domba 
                                            @endif
                                             _ {{ $getAnimal->kode }} _ {{ $getAnimal->kode_kandang }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <br>

                                    <!-- Tambah hewan terjangkit -->

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">Kembali</button>
                                    <button type="submit" class="btn btn-primary"> Simpan </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            @endsection
            @section('js')
                {{-- <script type="text/javascript" src="{{ asset('admin\assets\plugins\jquery\js\jquery.min.js') }}"></script>
                <script type="text/javascript" src="{{ asset('admin\assets\plugins\jquery-ui\js\jquery-ui.min.js') }}"></script> --}}

                
                <!-- Select 2 js -->
                <script type="text/javascript" src="{{ asset('admin\assets\plugins\select2\js\select2.full.min.js') }}"></script>
                
                <script>
                    function loadPreview(input) {
                        var data = $(input)[0].files;
                        $.each(data, function(index, file) {
                            if (/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)) {
                                var fRead = new FileReader();
                                fRead.onload = (function(file) {
                                    return function(e) {
                                        var img = $('<img/>').addClass('thumb').attr('src', e.target
                                            .result); //create image thumb element
                                        $('#thumb-output').append(img);
                                    };
                                })(file);
                                fRead.readAsDataURL(file);
                            }
                        });
                    }
                </script>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $(".btn-success").click(function() {
                            var lsthmtl = $(".clone").html();
                            $(".increment").after(lsthmtl);
                        });
                        $("body").on("click", ".alfa", function() {
                            $(this).parents(".hdtuto control-group lst").remove();
                        });
                    });
                </script>

                <script type="text/javascript">
                    $(document).ready(function() {
                        $('.js-example-basic-single').select2();
                    });
                </script>

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
