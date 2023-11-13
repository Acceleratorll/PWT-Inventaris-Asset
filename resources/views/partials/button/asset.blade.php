<a href="{{ route('admin.assets.edit', ['asset' => $id]) }}" data-original-title="Edit" class="edit btn btn-success edit">
    Edit
</a>
<button id="details" data-id="{{ $id }}" data-name="{{ $name }}" data-original-title="Details" class="details btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
    Details
</button>

<button id="delete" data-id="{{ $id }}" data-original-title="Delete" class="delete btn btn-danger">
    Delete
</button>
    
<form action="{{ route('admin.assets.destroy',['asset' => $id]) }}" id="deleteForm" method="post">
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
                title: 'Delete Asset',
                text: 'Are you sure you want to delete this Asset?',
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
                        url: `{{ route("admin.assets.destroy", ["asset" => ":assetId"]) }}`.replace(':assetId', defaultId),
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function (response) {
                            Swal.fire({
                                title: 'Asset Deleted Successfully',
                                icon: 'success',
                                timer: 1700,
                            });
                            Swal.showLoading();
    
                            $('#table').DataTable().ajax.reload();
                        },
                        error: function (error) {
                            console.error('Error:', error);
                            Swal.fire('Error', 'Failed to delete Asset', 'error');
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
                url: `{{ route("admin.assets.show", ["asset" => ":assetId"]) }}`.replace(':assetId', defaultId),
                success: function (response) {
                    console.log(response);
                    var content = '<ul>';
                    $.each(response, function(index, room) {
                        content += '<li>Rooms ' + room.name + ' jumlah asset: '+ room.pivot.qty +'</li>';
                        content += '<div class="divider"></div>';
                    });
                    content += '</ul>';
                    
                    Swal.fire({
                        title: defaultName+' Rooms List',
                        icon: 'info',
                        html: content,
                    });
                },
                error: function (error) {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to See the Details of Asset', 'error');
                },
            });
        });
    });
</script>