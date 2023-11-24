<a href="{{ route('admin.types.edit', ['type' => $id]) }}" data-original-title="Edit" class="edit btn btn-success edit">
    Edit
</a>

<button id="details" data-id="{{ $id }}" data-name="{{ $name }}" data-original-title="Details" class="details btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
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
                title: 'Delete Asset Type',
                text: 'Are you sure you want to delete this Asset Type?',
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

        $('.details').off().on('click', function(){
            var detailButton = $(this);
            var defaultId = detailButton.data('id');
            var defaultName = detailButton.data('name');
            console.log(defaultId);
            $.ajax({
                type: 'GET',
                url: `{{ route("admin.types.show", ["type" => ":typeId"]) }}`.replace(':typeId', defaultId),
                success: function (response) {
                    var content = '<ul>';
                        $.each(response, function(index, data) {
                            console.log(data);
                        content += '<li>Asset <b>' + data.name+'</b> dengan Total Barang '+data.total+'</li>';
                        content += '<div class="divider"></div>';
                    });
                    content += '</ul>';
                    
                    Swal.fire({
                        title: 'Asset List with Type '+defaultName,
                        icon: 'info',
                        html: content,
                    });
                },
                error: function (error) {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to See the Details of Type', 'error');
                },
            });
        });
    });
</script>