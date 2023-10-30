@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<table class="table table-movement table-bordered" id="table-movement" name="table-movement" class="display">
    <thead class="thead-dark">
        <tr>
            <th>Asset</th>
            <th>Room</th>
            <th>Di Update</th>
            <th>Di Buat</th>
            <th>Actions</th>
        </tr>
    </thead>    
</table>
@stop

@section('css')
@stop

@section('js')

<script> 
function toast(){
    Swal.fire({
        type:'success',
        icon:'success',
    });
}

document.addEventListener('DOMContentLoaded', function () {
    let table = new DataTable('#table-movement');
});

$(document).ready(function() {
    $('#table-movement').DataTable();
    toast();
});
</script>
@stop
