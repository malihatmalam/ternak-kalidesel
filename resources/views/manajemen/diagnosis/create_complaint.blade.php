@extends('layout_master.master')

@section('content')

    <link href="{{ asset('admin\assets\plugins\limitless\css\custom-limitless-components.css') }}" rel="stylesheet"
        type="text/css">

    <!-- INPUTS -->
    <div class="main">
        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="container-fluid">
                <h2 class="page-title"><b> Formulir Konsultasi Penyakit Ternak </b></h2>
                <div class="panel">

                    <div class="panel-heading">
                        <h2 class="panel-title">Konsultasi Penyakit Ternak</h2>
                    </div>
                    @if (count($errors) > 0)
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="panel-body">
                        <form action="{{ route('complaint.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <!-- Gejala penyakit hewan -->
                            <label for="keluhan-hewan" class="col-sm-3 col-form-label"> Gejala Penyakit </label>
                            <textarea class="form-control" placeholder="Gejala/keluhan dari hewan ternak..." name="complaint" rows="4"
                                required></textarea>
                            <br>

                            <!-- Gambar Gejala -->
                            <label for="type-hewan" class="col-sm-3 col-form-label"> Gambar Gejala </label>
                            <div class="form-group row">
                                <div class="col-lg-10">
                                    <input type="file" class="file-input-extensions" multiple="multiple" name="image[]">
                                    <br>
                                    <span class="form-text text-muted">Maksimal 5 foto, serta maksimal ukuran file sebesar 1 mb (megabyte). </span>
                                </div>
                            </div>
                            <br>

                            <div class="form-group row">
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-primary"> Simpan </button>
                                    <a href="{{ route('diagnosis.index.farmer') }}" class="btn btn-warning"> Kembali </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            @endsection
            @section('js')
                <script
                    src="{{ asset('admin\assets\plugins\limitless\js\limitless-material\main\bootstrap.bundle.min.js') }}">
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

                <script
                    src="{{ asset('admin\assets\plugins\limitless\js\limitless-material\demo_pages\uploader_bootstrap.js') }}">
                </script>
            @endsection
