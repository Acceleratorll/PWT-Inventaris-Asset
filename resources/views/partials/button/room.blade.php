<a href="{{ route('admin.rooms.edit', ['room' => $id]) }}" data-original-title="Edit" class="edit btn btn-success edit">
    Edit
</a>
<button id="details" data-id="{{ $id }}" data-name="{{ $name }}" data-original-title="Details" class="details btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
    Details
</button>

<button id="delete" data-id="{{ $id }}" data-original-title="Delete" class="delete btn btn-danger">
    Delete
</button>
    
<form action="{{ route('admin.rooms.destroy',['room' => $id]) }}" id="deleteForm" method="post">
    @csrf
    @method("DELETE")
</form>

<script>
    $(document).ready(function(){
        $('.delete').on('click', function () {
            var deleteButton = $(this);
            var defaultId = deleteButton.data('id');
            console.log(defaultId);
            Swal.fire({
                title: 'Delete Room',
                text: 'Are you sure you want to delete this Room?',
                type: 'warning',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                console.log(result, defaultId);
                if (result.value == true) {
                    console.log('confirmed');
                    $.ajax({
                        type: 'POST',
                        url: `{{ route("admin.rooms.destroy", ["room" => ":roomId"]) }}`.replace(':roomId', defaultId),
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Room Deleted Successfully',
                                icon: 'success',
                                timer: 1700,
                            });
                            Swal.showLoading();
    
                            $('#table').DataTable().ajax.reload();
                        },
                        error: function (error) {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Failed to delete Room', 'error');
                        },
                    });
                }
            });
        });

        $('.details').off().on('click', function(){
            var detailButton = $(this);
            var defaultId = detailButton.data('id');
            var defaultName = detailButton.data('name');
            console.log(defaultId);
            $.ajax({
                type: 'GET',
                url: `{{ route("admin.rooms.show", ["room" => ":roomId"]) }}`.replace(':roomId', defaultId),
                success: function (response) {
                    var content = '<ul>';
                        $.each(response, function(index, data) {
                        content += '<li>Asset ' + data.asset.name + ' Jumlah : '+ data.qty +' | '+data.condition.name+'</li>';
                        content += '<div class="divider"></div>';
                    });
                    content += '</ul>';
                    
                    Swal.fire({
                        title: 'Asset List Room '+defaultName,
                        icon: 'info',
                        html: content,
                    });
                },
                error: function (error) {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to See the Details of Room', 'error');
                },
            });
        });
    });
</script>