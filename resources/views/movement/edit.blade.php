@extends('adminlte::page')

@section('title', 'Edit Asset')

@section('content_header')
    <h1>Edit Asset</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('admin.movements.update', ['movement' => $movement->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Pilih Asset</label>
                    <select class="form-control" type="text" name="asset_id" id="asset_id" required>
                        <option value="{{ $movement->asset_id }}" selected>{{ $movement->asset->name }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="qty">QTY</label>
                    <input type="number" class="form-control" value="{{ $movement->qty }}" placeholder="Masukkan Jumlah Asset" name="qty" id="qty" required/>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="from_room_id">Dari Ruangan</label>
                    <select class="form-control select2" name="from_room_id" id="from_room_id" required>
                        <option value="{{ $movement->from_room_id }}" selected>{{ $movement->fromRoom->name }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="to_room_id">Ke Ruangan</label>
                    <select class="form-control select2" name="to_room_id" id="to_room_id" required>
                        <option value="{{ $movement->to_room_id }}" selected>{{ $movement->toRoom->name }}</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="form-control btn btn-outline-success">Submit</button>
    </form>
</div>
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
            selectInput(to_room, to_room_url, to_room_title);
            selectInput(from_room, from_room_url, from_room_title);
            selectInput(asset, asset_url, asset_title);
        });
    </script>
@endsection