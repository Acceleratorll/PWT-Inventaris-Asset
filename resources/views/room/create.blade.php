@extends('adminlte::page')

@section('title', 'Add Room')

@section('content_header')
    <h1>Add Room</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('admin.rooms.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="name">Nama Ruangan</label>
                    <input class="form-control" type="text" name="name" id="name" placeholder="Masukkan Nama Ruangan" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="location">Lokasi</label>
                    <input class="form-control" type="text" name="location" id="location" placeholder="Masukkan Letak Lokasi" required>
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
<script>
</script>
@endsection