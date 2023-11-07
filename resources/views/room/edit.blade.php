@extends('adminlte::page')

@section('title', 'Edit Room')

@section('content_header')
    <h1>Edit Room</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('admin.rooms.update', ['room'=>$room->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="name">Nama Room</label>
                    <input class="form-control" value="{{ $room->name }}" type="text" name="name" id="name" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col">
                <div class="form-group">
                    <label for="location">Lokasi</label>
                    <input class="form-control" value="{{ $room->location }}" type="text" name="location" id="location" required>
                </div>
            </div>
        </div>
        <button type="submit" class="form-control btn btn-outline-success">Submit</button>
    </form>
</div>
@endsection

@section('adminlte_js')
    <script>
    </script>
@endsection