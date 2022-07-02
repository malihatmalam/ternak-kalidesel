@extends('layout_master.master')

@section('content')

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
                <h2 class="page-title"><b> Data Konsultasi Penyakit Ternak </b></h2>
                <div class="panel">

                    <div class="panel-heading">
                        <h2 class="panel-title">Data Penyakit Ternak</h2>
                    </div>
                    <div class="panel-body">
                        <!-- Gejala penyakit hewan -->
                        <label for="keluhan-hewan" class="col-sm-3 col-form-label"> Gejala Penyakit </label>
                        <textarea class="form-control" placeholder="Gejala/keluhan dari hewan ternak..." name="complaint" rows="4"
                            disabled>{{ $complaint->complaint }}</textarea>
                        <br>

                        @if ($complaint->images->count())
                                <div class="row photos">
                                    @foreach ($complaint->images as $image)
                                        <div class="col-sm-6 col-md-4 col-lg-3 item  mt-3">
                                            <a href="{{ asset('Diagnosis/' . $image->image) }}"
                                                data-lightbox="photos">
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

                    </div>
                </div>

                <div class="panel">

                    <div class="panel-heading">
                        <h2 class="panel-title">Diagnosis dan Perawatan Penyakit Ternak</h2>
                    </div>
                    @if (count($errors) > 0)
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="panel-body">

                        <form action="{{ route('diagnosis.store', $complaint->code) }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <!-- Diagnosis penyakit hewan -->
                            <label for="diagnosa-hewan" class="col-sm-3 col-form-label"> Diagnosis Penyakit </label>
                            <textarea class="form-control" placeholder="Diagnosa ..." name="diagnosis" rows="4" required></textarea>
                            <br>

                            <!-- Treatment hewan -->
                            <label for="treatment-hewan" class="col-sm-3 col-form-label"> Perawatan/pengobatan </label>
                            <textarea class="form-control" placeholder="Perawatan atau pengobatan ..." name="treatment" rows="4" required></textarea>
                            <br>

                            <div class="form-group row">
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-primary"> Simpan </button>
                                    <a href="{{ route('diagnosis.index.doctor') }}" class="btn btn-warning"> Kembali </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            @endsection
            @section('js')
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

                <script src="{{ asset('admin\assets\plugins\limitless\js\limitless-material\main\bootstrap.bundle.min.js') }}">
                </script>
                <script src="{{ asset('admin\assets\plugins\limitless\js\limitless-material\js\app.js') }}"></script>
                <script
                    src="{{ asset('admin\assets\plugins\limitless\js\limitless-material\plugins\uploaders\fileinput\plugins\purify.min.js') }}">
                </script>
                <script
                    src="{{ asset('admin\assets\plugins\limitless\js\limitless-material\plugins\uploaders\fileinput\plugins\sortable.min.js') }}">
                </script>
                <script
                    src="{{ asset('admin\assets\plugins\limitless\js\limitless-material\plugins\uploaders\fileinput\fileinput.min.js') }}">
                </script>

                <script src="{{ asset('admin\assets\plugins\limitless\js\limitless-material\demo_pages\uploader_bootstrap.js') }}">
                </script>
            @endsection
