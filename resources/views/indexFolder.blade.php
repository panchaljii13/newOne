@extends('Layout')

@section('title', 'Document Management System')

@section('content')
@include('include.Header')

<section>
<div class="py-5">
    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center mb-4 mt-5 text-black">Document Management System</h3>

                    {{-- Display validation errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Display session error messages --}}
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Create Folder Button -->
                    <div class="text-center mb-4">
                        <a class="btn btn-gradient mb-3" href="#" data-bs-toggle="modal" data-bs-target="#createFolderModal" style="width: 100%; max-width: 200px;">
                            <i class="fas fa-folder-plus"></i> Create Folder
                        </a>
                    </div>

                    <!-- Create Folder Modal -->
                    <div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content shadow-lg rounded-lg">
                                <div class="modal-header bg-gradient-primary text-white py-3">
                                    <h5 class="modal-title fw-bold" id="createFolderModalLabel">
                                        <i class="fas fa-folder-plus me-2"></i>Create New Folder
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <form id="createFolderForm" action="{{ route('store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="form_type" value="create">
                                        <input type="hidden" name="parent_id" value="{{ $parentId ?? null }}">
                                        <div class="mb-3">
                                            <label for="folderName" class="form-label fs-5">Folder Name</label>
                                            <input type="text" id="folderName" name="name" class="form-control rounded-pill p-2" placeholder="Enter folder name..." required>
                                            @if ($errors->has('name'))
                                                <span class="text-danger small">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                                <i class="fas fa-times"></i> Close
                                            </button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                <i class="fas fa-folder-plus"></i> Create Folder
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('name') && old('form_type') === 'create')
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var createFolderModal = new bootstrap.Modal(document.getElementById('createFolderModal'));
                                createFolderModal.show(); // Open Create Modal if validation error for create folder
                            });
                        </script>
                    @endif


                    <!-- Script to Refresh Page on Modal Close -->
                    <script>
                        document.getElementById('createFolderModal').addEventListener('hidden.bs.modal', function () {
                            location.reload(); // Automatically refresh the page when modal is closed
                        });
                    </script>
                    <!-- Script to Reopen Modal if there are validation errors -->

                    <!-- --------------------------------------------------------------------------------------------------------------------------- -->


<!-- -------------------------------------------------------------------------------------------------------------------------------------------------------- -->
<div id="folder-list" class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="thead-light">
            <tr style="text-align: center;"> <!-- Center align the header -->
                <th>Folder Name</th>
                <th>Created At</th>
                <th>Edit</th>
                <th>Public/Private</th>
                <th>Download</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($folders as $folder)
                <tr class="bg-white shadow-sm rounded-3" style="text-align: center; vertical-align: middle;"> <!-- Center align the row content -->
                    <td>
                        <div class="d-flex align-items-center"> <!-- Center align the folder name content -->
                            <img src="https://st3.depositphotos.com/1688079/14959/i/450/depositphotos_149595992-stock-photo-folder-icon-glassy-soft-green.jpg" class="rounded-circle img-fluid me-3" height="40" width="40">
                            <a href="{{ route('show', $folder->id) }}" class="text-dark fw-bold">{{ $folder->name }}</a>
                        </div>
                    </td>
                    <td>{{ $folder->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="#" class="edit-folder-btn " data-folder-id="{{ $folder->id }}" data-folder-name="{{ $folder->name }}">
                            <i style="font-size: 24px;" class="fas fa-edit"></i>
                        </a>
                    </td>
                     <!-- Edit Folder Modal -->
                                        <div class="modal fade" id="editFolderModal" tabindex="-1" role="dialog"
                                            aria-labelledby="editFolderModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg"> <!-- Adjust size to match create modal -->
                                                <div class="modal-content shadow-lg rounded-lg">
                                                    <!-- Add shadow and rounded styles -->
                                                    <div class="modal-header bg-gradient-primary text-white py-3">
                                                        <!-- Use a gradient background -->
                                                        <h5 class="modal-title fw-bold" id="editFolderModalLabel">
                                                            <i class="fas fa-edit me-2"></i>Edit Folder
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="editFolderForm" method="POST"
                                                        action="{{ route('updateFolder', 'folderId') }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body p-4"> <!-- Add padding to the body -->
                                                            <div class="mb-3">
                                                                <label for="folder-name" class="form-label fs-5">Folder
                                                                    Name</label>
                                                                <input type="text" class="form-control rounded-pill p-2"
                                                                    id="folder-name" name="name"
                                                                    placeholder="Enter folder name..." required>
                                                                <span class="text-danger"
                                                                    id="folderNameError">{{ $errors->first('name') }}</span>
                                                                <!-- Display validation error -->
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button"
                                                                class="btn btn-secondary rounded-pill px-4"
                                                                data-bs-dismiss="modal">
                                                                <i class="fas fa-times"></i> Close
                                                            </button>
                                                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                                <i class="fas fa-save"></i> Save changes
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($errors->has('name') && old('form_type') === 'edit')
                                            <script>
                                                document.addEventListener("DOMContentLoaded", function () {
                                                    // Prefill the modal input with the old input value
                                                    document.getElementById('folder-name').value = "{{ old('name') }}";

                                                    // Show the edit folder modal
                                                    var editFolderModal = new bootstrap.Modal(document.getElementById('editFolderModal'));
                                                    editFolderModal.show();
                                                });
                                            </script>
                                        @endif
<!-- --------------------------------------------------------------------------------------------------------------------------- -->



                    <td>
                        @if (is_null($folder->parent_id))
                            <form method="POST" action="{{ route('togglePublic', $folder->id) }}" class="d-inline me-2" id="toggle-form-{{ $folder->id }}">
                                @csrf
                                @method('PUT')
                                <button type="button" class="btn btn-link p-0" style="border: none; background: none;" onclick="confirmToggle({{ $folder->id }})" title="{{ $folder->is_public ? 'Make Private' : 'Make Public' }}">
                                    <i class="{{ $folder->is_public ? 'fas fa-unlock-alt' : 'fas fa-lock' }}" style="font-size: 24px; color: {{ $folder->is_public ? 'green' : 'red' }};"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                    <td>
                        @if (is_null($folder->parent_id))
                            <a href="{{ route('download', $folder->id) }}" class="btn btn-link p-1 download-folder" data-folder-id="{{ $folder->id }}" title="Download Folder">
                                <i class="fa fa-download" style="font-size: 24px; color: #0072ff;"></i>
                            </a>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('destroy', $folder->id) }}" class="d-inline me-" id="delete-form-{{ $folder->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-link p-0" style="border: none; background: none;" onclick="confirmDelete({{ $folder->id }})" title="Delete Folder">
                                <i style="font-size: 24px; color: red;" class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


                </div>
            </div>
        </div>
    </div>
</div>


</section>

<style>
    /* Body background */
    body {
        background-color: #f0f0f0;
    }

    /* Card styling */
    .card {
        border-radius: 15px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Button styling */
    .btn-gradient {
        background: linear-gradient(45deg, #6a11cb, #2575fc);
        color: #fff;
        border-radius: 25px;
    }

    .btn-gradient:hover {
        background: linear-gradient(45deg, #2575fc, #6a11cb);
    }

    .list-group-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Modal header */
    .modal-header {
        background-color: #007bff;
        /* Bootstrap primary color */
        color: white;
    }

    /* Modal body */
    .modal-body {
        background-color: #f8f9fa;
        /* Light background for body */
    }

    /* Button styles */
    .btn-primary {
        background-color: #28a745;
        /* Green button */
        border: none;
    }

    .btn-primary:hover {
        background-color: #218838;
        /* Darker green on hover */
    }

    .btn-secondary {
        background-color: #6c757d;
        /* Secondary color */
        border: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        /* Darker on hover */
    }

    /* Input styles */
    .form-control:focus {
        border-color: #007bff;
        /* Change focus border color */
        box-shadow: 0 0 5px rgba(0, 123, 255, .5);
        /* Add shadow effect */
    }

    /* Error message styles */
    .text-danger {
        font-weight: bold;
        /* Bold error messages */
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script>

    document.querySelectorAll('.download-folder').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent the default action

            const folderId = this.getAttribute('data-folder-id');

            // SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to download this folder!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, download it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the download route
                    window.location.href = "{{ url('download') }}/" + folderId;
                }
            });
        });
    });
    function confirmDelete(folderId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${folderId}`).submit();
            }
        });
    }

    function confirmToggle(folderId) {
        Swal.fire({
            title: 'Change Visibility',
            text: "Are you sure you want to change the visibility of this folder?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`toggle-form-${folderId}`).submit();
            }
        });
    }

    //    -------------------------------------------------------------Edite--------------------------------------------
    // Edit Folder Functionality
    $(document).on('click', '.edit-folder-btn', function (e) {
        e.preventDefault();

        var folderId = $(this).data('folder-id');
        var folderName = $(this).data('folder-name');

        // Prefill modal with folder details
        $('#folder-name').val(folderName);
        $('#editFolderForm').attr('action', '/folders/' + folderId);

        // Clear previous error messages
        $('#folderNameError').text('');

        // Show modal
        $('#editFolderModal').modal('show');
    });

    // Handle form submission
    $('#editFolderForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        var form = $(this);
        var actionUrl = form.attr('action');

        // Perform AJAX request
        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: form.serialize(),
            success: function (response) {
                // On success, hide modal and refresh
                $('#editFolderModal').modal('hide');
                location.reload(); // Reload the page to reflect the changes
            },
            error: function (xhr) {
                // Clear previous error messages
                $('#folderNameError').text('The name has already been taken');

                // Check if response contains validation errors
                if (xhr.status === 422) { // 422 is Laravel's validation error status code
                    var errors = xhr.responseJSON.errors;

                    // Check and display specific error for folder name
                    if (errors.folder_name) {
                        $('#folderNameError').text(errors.folder_name[0]); // Show the first error for folder name
                    } else {
                        $('#folderNameError').text('The name has already been taken.'); // Default error message
                    }
                }
            }
        });
    });

    // Reload page after modal is closed (if needed)
    $('#editFolderModal').on('hidden.bs.modal', function () {
        // Optionally, you can reload the page here if you want
        // location.reload(); 
    });



    // ---------------------------------------------------------------------------------------------------------------------------



</script>
@endsection