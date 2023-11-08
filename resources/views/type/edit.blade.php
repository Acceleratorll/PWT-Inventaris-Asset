@extends('adminlte::page')

@section('title', 'Edit Type')

@section('content_header')
    <h1>Edit Type</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('admin.types.update', ['type'=>$type->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="name">Nama Type</label>
                    <input class="form-control" value="{{ $type->name }}" type="text" name="name" id="name" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="isMoveable">isMoveable</label>
                    <select class="form-control" name="isMoveable" id="isMoveable" required>
                        <option value="{{ $type->isMoveable }}" selected>{{ $type->isMoveable == 1 ? 'Iya' : 'Tidak' }}</option>
                        <option value="0">Tidak</option>
                        <option value="1">Iya</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="form-control btn btn-outline-success">Submit</button>
    </form>
</div>
@endsection

@section('adminlte_js')
    <script>
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
    </script>
@endsection