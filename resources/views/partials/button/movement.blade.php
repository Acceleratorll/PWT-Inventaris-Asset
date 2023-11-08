<a href="{{ route('admin.movements.edit', ['movement' => $id]) }}" data-original-title="Edit" class="edit btn btn-success edit">
    Edit
</a>

<button id="delete" data-id="{{ $id }}" data-original-title="Delete" class="delete btn btn-danger">
    Delete
</button>
    
<form action="{{ route('admin.movements.destroy',['movement' => $id]) }}" id="deleteForm" method="post">
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
                title: 'Delete Movement',
                text: 'Are you sure you want to delete this Movement?',
                type: 'warning',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                console.log(result);
                if (result.value == true) {
                    console.log('confirmed');
                    $.ajax({
                        type: 'POST',
                        url: `{{ route("admin.movements.destroy", ["movement" => ":movementId"]) }}`.replace(':movementId', defaultId),
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Movement Deleted Successfully',
                                icon: 'success',
                                timer: 1700,
                            });
                            Swal.showLoading();
    
                            $('#table').DataTable().ajax.reload();
                        },
                        error: function (error) {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Failed to delete Movement', 'error');
                        },
                    });
                }
            });
        });
    });
</script>