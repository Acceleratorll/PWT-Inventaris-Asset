@extends('adminlte::page')

@section('title', 'Add Asset')

@section('content_header')
    <h1>Add Asset</h1>
@stop

@section('content')
<div class="container">
    <form action="{{ route('admin.asset.stock.store') }}" method="POST">
        @csrf
        <div class="form-row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="asset_id">Pilih Asset</label>
                    <select class="form-control select2" name="asset_id" id="asset_id" required></select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="room_id">Pilih Ruangan</label>
                    <select class="form-control select2" name="room_id[]" id="room_id" multiple required></select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="total">Stock Tambahan</label>
                    <input class="form-control" placeholder="Masukkan Tambahan" type="number" name="total" id="total" required>
                </div>
            </div>
        </div>
        <div id="conditions-container"></div>
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
    const room_title = 'Pilih Tipe Room';
    const asset = document.getElementById("asset_id");
    const asset_url = '{{ route("admin.select.assets") }}';
    const asset_title = 'Pilih Tipe Asset';

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

        selectInput(room, room_url, room_title);
        selectInput(asset, asset_url, asset_title);

        $('#room_id').change(function() {
            // Hapus input kondisi lama
            $('#conditions-container').empty();
        
            // Dapatkan ruangan yang dipilih
            var selectedRooms = $(this).val();
            
            var roomNames = $("#room_id option:selected").map(function() {
                return $(this).text();
            }).get();
            console.log(roomNames);
                
            // Tampilkan input untuk setiap kondisi
            $.each(selectedRooms, function(index, roomId) {
                $('#conditions-container').append(
                    '<div class="divider"></div>'+
                    '<h3>Ruangan '+ roomNames[index] +'</h3>'+
                    '<div class="form-row">' +
                        '<div class="col-md-2">' +
                            '<div class="form-group">' +
                                '<label for="qty_good_' + roomId + '">Jumlah Asset yang Baik</label>' +
                                '<input class="form-control" type="number" placeholder="Jumlah" name="qty_good[' + roomId + ']" id="qty_good_' + roomId + '" required>' +
                            '</div>' +
                        '</div>' +
                        '<div class="col-md-2">' +
                            '<div class="form-group">' +
                                '<label for="qty_bad_' + roomId + '">Jumlah Asset yang Buruk</label>' +
                                '<input class="form-control" type="number" placeholder="Jumlah" name="qty_bad[' + roomId + ']" id="qty_bad_' + roomId + '" required>' +
                            '</div>' +
                        '</div>' +
                    '</div>'
                );
            });
        });
    });
</script>
@endsection