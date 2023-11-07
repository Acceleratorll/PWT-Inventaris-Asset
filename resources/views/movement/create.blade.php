@extends('adminlte::page')

@section('title', 'Add Asset')

@section('content_header')
    <h1>Add Asset</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('admin.assets.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="name">Nama Asset</label>
                    <input class="form-control" type="text" name="name" id="name" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="asset_type_id">Tipe Asset</label>
                    <select class="form-control" name="asset_type_id" id="asset_type_id" required>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="item_code">Code</label>
                    <input class="form-control" type="text" name="item_code" id="item_code" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="total">Total</label>
                    <input class="form-control" type="number" name="total" id="total" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="acquition">Diterima</label>
                    <input class="form-control" type="datetime-local" value="{{ now() }}" name="acquition" id="acquition" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="last_move_date">Terakhir Pindah</label>
                    <input class="form-control" type="datetime-local" value="{{ now() }}" name="last_move_date" id="last_move_date" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="room_id">Pilih Ruangan</label>
                    <select class="form-control select2" name="room_id" id="room_id" required>
                        <option value="" selected disabled>Pilih Ruangan</option>
                        @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="condition">Kondisi</label>
                    <select class="form-control" name="condition" id="condition" required>
                        <option value=""selected disabled>Pilih Kondisi</option>
                        <option value="good">Baik</option>
                        <option value="bad">Buruk</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="note">Catatan</label>
                    <textarea class="form-control" name="note" id="note" required></textarea>
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
    const room = document.getElementById("room_id");
    const room_url = '{{ route("admin.select.rooms") }}';
    const room_title = 'Pilih Ruangan';
    const assetType = document.getElementById("asset_type_id");
    const assetType_url = '{{ route("admin.select.asset-types") }}';
    const assetType_title = 'Pilih Type Asset';
    
    $(document).ready(function() {
        selectInput(room, room_url, room_title);
        selectInput(assetType, assetType_url, assetType_title);
    });
</script>
@endsection