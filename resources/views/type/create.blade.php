@extends('adminlte::page')

@section('title', 'Add Type')

@section('content_header')
    <h1>Add Type</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('admin.types.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="name">Nama Tipe</label>
                    <input class="form-control" type="text" name="name" id="name" placeholder="Masukkan Nama Tipe Asset" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="isMoveable">isMoveable</label>
                    <select class="form-control" name="isMoveable" id="isMoveable" required>
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

@section('css')
    
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