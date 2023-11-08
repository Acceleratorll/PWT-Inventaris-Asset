@extends('adminlte::page')

@section('title', 'Room')

@section('content_header')
    <h1>Room</h1>
@stop

@section('content')
<a href="{{ route('admin.rooms.create') }}" class="btn btn-outline-primary">Add Room</a>
<table class="table table-striped" style="width:100%" id="table" name="table" class="display">
    <thead class="thead-dark text-center">
        <tr>
            <th>Nama</th>
            <th>Location</th>
            <th>Terakhir Update</th>
            <th>Di Buat</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="text-center"></tbody>
    <tfoot>
        <tr>
            <th>Nama</th>
            <th>Location</th>
            <th>Terakhir Update</th>
            <th>Di Buat</th>
        </tr>
    </tfoot>
</table>
@stop

@section('css')
<style>
    .dataTables_filter {
   width: 50%;
   float: right;
   text-align: right;
}

</style>
@stop

@section('js')

<script> 
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
    
    var columns = [
        { data: 'name', name: 'name' },
        { data: 'location', name: 'location' },
        { data: 'formatted_updated_at', name: 'formatted_updated_at' },
        { data: 'formatted_created_at', name: 'formatted_created_at' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
    ];

    $('#table tfoot th').each( function (i) {
        var title = $('#table thead th').eq( $(this).index() ).text();
        $(this).html( '<input room="text" placeholder="'+title+'" data-index="'+i+'" />' );
    } );
    
    var table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        searchable: true,
        paging: false,
        scrollCollapse: true,
        scrollX: true,
        scrollY: 300,
        ajax: '{{ route('admin.table.rooms') }}',
        columns: columns,
        select: true,
        fixedColumns: {
            left: 1
        },
    });

    $(table.table().container() ).on( 'keyup', 'tfoot input', function () {
        table
            .column( $(this).data('index') )
            .search( this.value )
            .order([])
            .draw();
    });
});
</script>
@stop