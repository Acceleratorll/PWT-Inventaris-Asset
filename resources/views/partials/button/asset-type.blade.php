<a href="{{ route('admin.types.edit', ['type' => $id]) }}" data-original-title="Edit" class="edit btn btn-success edit">
    Edit
</a>
<button id="show-outgoing-products" data-id="{{ $id }}" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
    Details
</button>

<button id="delete" data-id="{{ $id }}" data-original-title="Delete" class="delete btn btn-danger">
    Delete
</button>
    
<form action="{{ route('admin.types.destroy',['type' => $id]) }}" id="deleteForm" method="post">
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
                title: 'Delete Type',
                text: 'Are you sure you want to delete this Type?',
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
                        url: `{{ route("admin.types.destroy", ["type" => ":typeId"]) }}`.replace(':typeId', defaultId),
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Type Deleted Successfully',
                                icon: 'success',
                                timer: 1700,
                            });
                            Swal.showLoading();
    
                            $('#table').DataTable().ajax.reload();
                        },
                        error: function (error) {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Failed to delete Type', 'error');
                        },
                    });
                }
            });
        });
    });
</script>