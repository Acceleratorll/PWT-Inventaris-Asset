@extends('adminlte::page')

@section('title', 'Asset')

@section('content_header')
    <h1>Asset</h1>
@stop

@section('content')
<a href="{{ route('admin.assets.create') }}" class="btn btn-outline-primary">Add Asset</a>
<table class="table table-striped" style="width:100%" id="table" name="table" class="display">
    <thead class="thead-dark text-center">
        <tr>
            <th>Nama</th>
            <th>Code</th>
            <th>Type</th>
            <th>Total</th>
            <th>Keadaan</th>
            <th>Barang Diterima</th>
            <th>Terakhir Pindah</th>
            <th>Terakhir Update</th>
            <th>Di Buat</th>
            <th>Catatan</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody class="text-center"></tbody>
    <tfoot>
        <tr>
            <th>Nama</th>
            <th>Code</th>
            <th>Type</th>
            <th>Total</th>
            <th>Keadaan</th>
            <th>Barang Diterima</th>
            <th>Terakhir Pindah</th>
            <th>Terakhir Update</th>
            <th>Catatan</th>
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
    var columns = [
        { data: 'name', name: 'name' },
        { data: 'code', name: 'code' },
        { data: 'type', name: 'type' },
        { data: 'total', name: 'total' },
        { data: 'condition', name: 'condition' },
        { data: 'acquition', name: 'acquition' },
        { data: 'last_move', name: 'last_move' },
        { data: 'formatted_updated_at', name: 'formatted_updated_at' },
        { data: 'formatted_created_at', name: 'formatted_created_at' },
        { data: 'note', name: 'note' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
    ];

    $('#table tfoot th').each( function (i) {
        var title = $('#table thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' );
    } );
    
    var table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        searchable: true,
        paging: false,
        scrollCollapse: true,
        scrollX: true,
        scrollY: 300,
        ajax: '{{ route('admin.table.assets') }}',
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