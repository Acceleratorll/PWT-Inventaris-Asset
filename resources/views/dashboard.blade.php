@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="card">
  <div class="card-body">
    <table class="table table-striped table-hover table-dark" style="width:100%" id="table" name="table" class="display">
        <thead class="thead-dark text-center">
            <tr>
                <th>Nama</th>
                <th width="30%">Dari</th>
                <th width="30%">Ke</th>
                <th>Qty</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="text-center"></tbody>
        <tfoot>
            <tr>
                <th>Nama</th>
                <th>Dari</th>
                <th>Ke</th>
                <th>Qty</th>
            </tr>
        </tfoot>
    </table>
  </div>
</div>
@stop

@section('css')
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
        { data: 'fromRoom', name: 'fromRoom' },
        { data: 'toRoom', name: 'toRoom' },
        { data: 'qty', name: 'qty' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
    ];

    $('#table-movement tfoot th').each( function (i) {
        var title = $('#table thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" placeholder="'+title+'" data-index="'+i+'" />' );
    });

    var table = $('#table').DataTable({
        processing: true,
        serverSide: true,
        searchable: true,
        paging: false,
        scrollCollapse: true,
        scrollX: true,
        scrollY: 300,
        ajax: '{{ route('admin.table.movements') }}',
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
