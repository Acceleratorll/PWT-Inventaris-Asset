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
            <div class="col-md-4">
                <div class="form-group">
                    <label for="name">Nama Asset</label>
                    <input class="form-control" placeholder="Masukkan Nama Asset" type="text" name="name" id="name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="item_code">Kode Asset</label>
                    <input class="form-control" placeholder="Masukkan Asset Code" type="text" name="item_code" id="item_code" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="total">Total Semua Asset</label>
                    <input class="form-control" placeholder="Masukkan Total Asset" type="number" name="total" id="total" required>
                </div>
            </div>
        </div>
        <div class="form-row">
            
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="acquition">Diterima</label>
                    <input class="form-control" placeholder="Tanggal Penerimaan Asset" type="datetime-local" value="{{ now() }}" name="acquition" id="acquition" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="last_move_date">Terakhir Pindah</label>
                    <input class="form-control" placeholder="Tanggal Terakhir Pindah" type="datetime-local" value="{{ now() }}" name="last_move_date" id="last_move_date" required readonly>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="room_id">Pilih Ruangan</label>
                    <select class="form-control select2" name="room_id[]" id="room_id" multiple required>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="asset_type_id">Tipe Asset</label>
                    <select class="form-control" name="asset_type_id" id="asset_type_id" required>
                        <option value="" selected disabled>Pilih Tipe Asset</option>
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
                    <label for="note">Catatan</label>
                    <textarea class="form-control" placeholder="Masukkan Catatan (Optional)" name="note" id="note"></textarea>
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
    const assetType = document.getElementById("asset_type_id");
    const assetType_url = '{{ route("admin.select.types") }}';
    const assetType_title = 'Pilih Tipe Asset';

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

        $('#room_id').select2();
        $('#room_id').change(function() {
            // Hapus input kondisi lama
            $('#conditions-container').empty();
        
            // Dapatkan ruangan yang dipilih
            var selectedRooms = $(this).val();
        
            var roomNames = $("#room_id option:selected").map(function() {
                return $(this).text();
            }).get();
                
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
            
        
        selectInput(assetType, assetType_url, assetType_title);
    });
</script>
@endsection