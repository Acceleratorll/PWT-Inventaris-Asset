@extends('adminlte::page')

@section('title', 'Add Asset')

@section('content_header')
    <h1>Add Asset</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('admin.movements.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="asset_id">Pilih Asset</label>
                    <select class="form-control" type="text" name="asset_id" id="asset_id" required></select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="condition_id">Kondisi</label>
                    <select class="form-control" type="text" name="condition_id" id="condition_id" required>
                        <option value="" selected disabled>Pilih Kondisi</option>
                        <option value="1">Good</option>
                        <option value="2">Bad</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="qty">QTY</label>
                    <input type="number" class="form-control" placeholder="Masukkan Jumlah Asset" name="qty" id="qty" required/>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="from_room_id">Dari Ruangan</label>
                    <select class="form-control select2" name="from_room_id" id="from_room_id" required></select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="to_room_id">Ke Ruangan</label>
                    <select class="form-control select2" name="to_room_id" id="to_room_id" required></select>
                </div>
            </div>
        </div>
        <button type="submit" class="form-control btn btn-outline-success">Submit</button>
    </form>
</div>
@endsection

@section('css')
    
@endsection

@section('adminlte_js')
<script src="{{ asset('/js/customSelect2.js') }}"></script>
<script>
    const asset = document.getElementById("asset_id");
    const asset_url = '{{ route("admin.select.assets") }}';
    const asset_title = 'Pilih Asset';
    const to_room = document.getElementById("to_room_id");
    const to_room_url = '{{ route("admin.select.rooms") }}';
    const to_room_title = 'Pilih Ruangan Yang Dituju';
    const from_room_title = 'Pilih Ruangan Awal';
    const from_room = document.getElementById("from_room_id");
    const from_room_url = '{{ route("admin.select.rooms") }}';
    
    $(document).ready(function() {
        if ('{{ Session::has('error') }}') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ Session::get('error') }}',
        });
    }

    if ('{{ Session::has('success') }}') {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ Session::get('success') }}',
        });
    }
    
        selectInput(to_room, to_room_url, to_room_title);
        selectInput(from_room, from_room_url, from_room_title);
        selectInput(asset, asset_url, asset_title);
    });
</script>
@endsection