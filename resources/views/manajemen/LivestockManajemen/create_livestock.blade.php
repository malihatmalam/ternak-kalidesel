@extends('layout_master.master')

@section('content')
    <!-- INPUTS -->
    <div class="main">
        <!-- MAIN CONTENT -->
        <div class="main-content">
            <div class="container-fluid">
                <h2 class="page-title"><b> Tambah Hewan Ternak </b></h2>
                <div class="panel">

                    <div class="panel-heading">
                        <h2 class="panel-title">Tambah Hewan Ternak</h2>
                    </div>
                    @if (count($errors) > 0)
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="panel-body">
                        <form action="{{ route('livestock.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <!-- jenis hewan -->
                            <div class="form-group">
                                <label for="jenis-hewan" class="col-sm-3 col-form-label"> Jenis Hewan </label>
                                <br>
                                <br>
                                <div class="container">
                                    <label class="fancy-radio">
                                        <input name="jenis" value="cow" type="radio">
                                        <span><i></i>Sapi</span>
                                    </label>
                                    <label class="fancy-radio">
                                        <input name="jenis" value="goat" type="radio">
                                        <span><i></i>Kambing</span>
                                    </label>
                                    <label class="fancy-radio">
                                        <input name="jenis" value="sheep" type="radio">
                                        <span><i></i>Domba</span>
                                    </label>
                                </div>
                            </div>
                            <br>

                            <!-- tipe -->
                            <label for="type-hewan" class="col-sm-3 col-form-label"> Tipe Hewan </label>
                            <input type="text" class="form-control" name="type" placeholder="Tipenya...">
                            <br>

                            <!-- kode-kandang -->
                            <label for="kode-kandang" class="col-sm-3 col-form-label"> Kode Kandang </label>
                            <input type="text" class="form-control" name="kode_kandang" placeholder="Kode Kandang....">
                            <br>

                            <!-- jenis kelamin -->
                            <div class="form-group">
                                <label for="jenis-kelamin-hewan" class="col-sm-3 col-form-label"> Jenis Kelamin Hewan
                                </label>
                                <br>
                                <br>
                                <div class="container">
                                    <label class="fancy-radio">
                                        <input name="jenis_kelamin" value="male" type="radio">
                                        <span><i></i>Jantan</span>
                                    </label>
                                    <label class="fancy-radio">
                                        <input name="jenis_kelamin" value="female" type="radio">
                                        <span><i></i>Betina</span>
                                    </label>
                                </div>
                            </div>
                            <br>

                            <!-- warna -->
                            <label for="warna-hewan" class="col-sm-3 col-form-label"> Warna Hewan </label>
                            <input type="text" class="form-control" name="warna" placeholder="Warna hewan ....">
                            <br>

                            <!-- tanggal lahir -->
                            <label for="tgl-lahir-hewan" class="col-sm-3 col-form-label"> Tanggal Lahir </label>
                            <input type="date" class="form-control" name="tgl_lahir"
                                placeholder="Tanggal lahir hewan ....">
                            <br>

                            <!-- deskripsi -->
                            <label for="deskripsi-hewan" class="col-sm-3 col-form-label"> Deskripsi Hewan </label>
                            <textarea class="form-control" placeholder="Deskripsi hewan..." name="description" rows="4"></textarea>
                            <br>

                            <!-- berat -->
                            <label for="berat-hewan" class="col-sm-3 col-form-label"> Berat Hewan </label>
                            <input type="text" class="form-control" name="berat" data-type="weight"
                                placeholder="Berat hewan .... (Kilogram)" >
                            <br>

                            <!-- harga -->
                            <label for="harga-hewan" class="col-sm-3 col-form-label"> Harga Hewan </label>
                            <input type="text" class="form-control" name="harga" data-type="currency"
                                placeholder="Harga hewan .... (Rupiah)">
                            <br>

                            <!-- area -->
                            <div class="form-group">
                                <label for="area" class="col-sm-3 col-form-label"> Desa  </label>
                                <br>
                                <br>
                                <div class="container">
                                    <label class="fancy-radio">
                                        <input name="area" value="Kalidesel" type="radio">
                                        <span><i></i>Kalidesel</span>
                                    </label>
                                    <label class="fancy-radio">
                                        <input name="area" value="Wonokampir" type="radio">
                                        <span><i></i>Wonokampir</span>
                                    </label>
                                </div>
                            </div>
                            <br>

                            <!-- foto hewan -->
                            <div class="form-group row">
                                <label for="foto_hewan" class="col-sm-3 col-form-label"> Upload Foto </label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control" name="foto">
                                </div>
                            </div>
                            <br>

                            <div class="form-group row">
                                <div class="col-sm-9">
                                    <button type="submit" class="btn btn-primary"> Simpan </button>
                                    <a href="/manager/livestock" class="btn btn-warning"> Kembali </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

            @endsection
            @section('js')
                <script>
                    // $(document).on('keyup', '#salary', function() {
                    //     // console.log('this', $(this))
                    //     formatCurrency($(this));
                    // });

                    // Jquery Dependency
                    $("input[data-type='currency']").on({
                        keyup: function() {
                            formatCurrency($(this));
                        }
                    });

                    $("input[data-type='weight']").on({
                        keyup: function() {
                            formatWeight($(this));
                        }
                    });

                    function formatNumber(n) {
                        // format number 1000000 to 1,234,567
                        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                    }

                    function formatCurrency(input, blur) {
                        // appends $ to value, validates decimal side
                        // and puts cursor back in right position.

                        // get input value
                        var input_val = input.val();

                        // don't validate empty input
                        if (input_val === "") {
                            return;
                        }

                        // original length
                        var original_len = input_val.length;

                        // initial caret position 
                        var caret_pos = input.prop("selectionStart");

                        // check for decimal
                        if (input_val.indexOf(",") >= 0) {

                            // get position of first decimal
                            // this prevents multiple decimals from
                            // being entered
                            var decimal_pos = input_val.indexOf(",");

                            // split number by decimal point
                            var left_side = input_val.substring(0, decimal_pos);
                            var right_side = input_val.substring(decimal_pos);

                            // add commas to left side of number
                            left_side = formatNumber(left_side);

                            // validate right side
                            right_side = formatNumber(right_side);

                            // Limit decimal to only 2 digits
                            right_side = right_side.substring(0, 2);

                            // join number by .
                            input_val = "Rp " + left_side + "," + right_side;

                        } else {
                            // no decimal entered
                            // add commas to number
                            // remove all non-digits
                            // console.log('input_val', input_val)
                            input_val = formatNumber(input_val);
                            input_val = "Rp " + input_val;

                        }

                        // send updated string to input
                        input.val(input_val);

                        // put caret back in the right position
                        var updated_len = input_val.length;
                        caret_pos = updated_len - original_len + caret_pos;
                        input[0].setSelectionRange(caret_pos, caret_pos);
                    }

                    function formatWeight(input, blur) {
                        // appends $ to value, validates decimal side
                        // and puts cursor back in right position.

                        // get input value
                        var input_val = input.val();

                        // don't validate empty input
                        if (input_val === "") {
                            return;
                        }

                        // original length
                        var original_len = input_val.length;

                        // initial caret position 
                        var caret_pos = input.prop("selectionStart");

                        // check for decimal
                        if (input_val.indexOf(",") >= 0) {

                            // get position of first decimal
                            // this prevents multiple decimals from
                            // being entered
                            var decimal_pos = input_val.indexOf(",");

                            // split number by decimal point
                            var left_side = input_val.substring(0, decimal_pos);
                            var right_side = input_val.substring(decimal_pos);

                            // add commas to left side of number
                            left_side = formatNumber(left_side);

                            // validate right side
                            right_side = formatNumber(right_side);

                            // Limit decimal to only 2 digits
                            right_side = right_side.substring(0, 2);

                            // join number by .
                            input_val = left_side + "," + right_side + " kilogram";

                        } else {
                            // no decimal entered
                            // add commas to number
                            // remove all non-digits
                            // console.log('input_val', input_val)
                            input_val = formatNumber(input_val);
                            input_val = input_val + " kilogram";

                        }

                        // send updated string to input
                        input.val(input_val);

                        // put caret back in the right position
                        var updated_len = input_val.length;
                        caret_pos = updated_len - original_len + caret_pos;
                        input[0].setSelectionRange(caret_pos, caret_pos);
                    }
                </script>
            @endsection
