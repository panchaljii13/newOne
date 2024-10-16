@extends('Layout')

@section('title', $folder->name)

@section('content')
@include('include.Header')

<div class="container py-4 mt-5 ">
    <div class="text-center mb-4 ">
        <img src="https://w7.pngwing.com/pngs/763/637/png-transparent-directory-icon-folder-miscellaneous-angle-rectangle-thumbnail.png"
            class="rounded-circle img-fluid me-3" height='40' width='40'>
        <h3 class="text-dark">{{ $folder->name }}</h3>
        <p class="text-muted">Created on: {{ $folder->created_at->format('d M Y') }}</p>
    </div>
    <button class="btn btn-dark mb-3" onclick="window.history.back();"> Back</button>

    <!-- Action buttons -->
    <div class="d-flex justify-content-between mb-4">
        <!-- <a class="btn btn-warning" href="{{ route('create', ['parentId' => $folder->id]) }}">
            <i class="fas fa-folder-plus"></i> Create Subfolder
        </a> -->
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        <a class="btn btn-warning" href="#" data-bs-toggle="modal" data-bs-target="#createFolderModal"
            data-parent-id="{{ $folder->id }}">
            <i class="fas fa-folder-plus"></i> Create Subfolder
        </a>

        <!-- Create Subfolder Modal -->
        <div class="modal fade @if ($errors->has('name') && old('form_type') == 'create') show @endif"
            id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true"
            style="@if ($errors->has('name') && old('form_type') == 'create') display: block; @endif">
            <div class="modal-dialog modal-md">
                <div class="modal-content shadow-lg rounded-3">
                    <div class="modal-header bg-gradient-primary text-white py-3">
                        <h5 class="modal-title fw-bold" id="createFolderModalLabel">
                            <i class="fas fa-folder-plus me-2"></i>Create Subfolder
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="createFolderForm" action="{{ route('store') }}" method="POST">
                            @csrf
                            <input type="hidden" id="modalParentId" name="parent_id" value="{{ old('parent_id') }}">
                            <input type="hidden" name="form_type" value="create">
                            <div class="mb-3">
                                <label for="folderName" class="form-label fs-5">Folder Name</label>
                                <input type="text" id="folderName" name="name" class="form-control rounded-pill p-2"
                                    placeholder="Enter folder name..." value="{{ old('name') }}" required>
                                @if ($errors->has('name'))
                                    <span class="text-danger small">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary rounded-pill px-4"
                                    data-bs-dismiss="modal">
                                    <i class="fas fa-times"></i> Close
                                </button>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-folder-plus"></i> Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript to handle setting the parent_id in the modal -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var createFolderModal = document.getElementById('createFolderModal');
                createFolderModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget; // Button that triggered the modal
                    var parentId = button.getAttribute('data-parent-id'); // Extract parent_id from data-* attributes
                    var modalParentIdInput = document.getElementById('modalParentId'); // Hidden input field in the modal

                    // Set the value of the hidden input field
                    modalParentIdInput.value = parentId;
                });
            });
        </script>


        <!-- ------------------------------------------------------------Add URL------------------------------------------------------------- -->



        <!-- Add URL Modal -->
        <div class="modal fade" id="addUrlModal" tabindex="-1" aria-labelledby="addUrlModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="addUrlModalLabel">Add URL</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addUrlForm" action="{{ route('storeUrl') }}" method="POST">
                            @csrf
                            <input type="hidden" id="folderId" name="folder_id" value="{{ $folder->id }}">
                            <!-- Hidden folder ID input -->
                            <div class="mb-3">
                                <label for="urlName" class="form-label">Name</label>
                                <input type="text" id="urlName" name="name" class="form-control"
                                    placeholder="Enter URL name" required>
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="url" class="form-label">URL</label>
                                <input type="url" id="url" name="url" class="form-control" placeholder="Enter URL"
                                    required>
                                @if ($errors->has('url'))
                                    <span class="text-danger">{{ $errors->first('url') }}</span>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add URL</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ------------------------------------------------------------------------------------------------------------------------- -->
        <!--  --------                                          upload Doc modal -->
        <!-- ------------------------------------------------------------------------------------------------------------------------- -->

        <div class="d-flex ">


            <!-- Button to open upload modal -->
            <button type="button" class="btn btn-success me-3 " data-bs-toggle="modal"
                data-bs-target="#uploadFileModal">
                Upload Doc
            </button>
            <!-- Button to open    Add URL modal -->
            <div class=" ">
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addUrlModal"
                    data-folder-id="{{ $folder->id }}"> <!-- Pass the folder ID here -->
                    Add URL
                </button>
            </div>

            <!-- Upload File Modal -->
            <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="uploadFileModalLabel">Upload File</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="uploadFileForm" action="{{ route('uploadFile', $folder->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <!-- File Input -->
                                <div class="mb-3">
                                    <label for="fileName" class="form-label">File Name</label>
                                    <input type="text" id="fileName" name="file_name" class="form-control"
                                        placeholder="Enter file name..." required>
                                    @if ($errors->has('file_name'))
                                        <span class="text-danger">{{ $errors->first('file_name') }}</span>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="fileInput" class="form-label">Choose File</label>
                                    <input type="file" id="fileInput" name="file" class="form-control" required>
                                    @if ($errors->has('file'))
                                        <span class="text-danger">{{ $errors->first('file') }}</span>
                                    @endif
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- -----------------------------------------------------------Subfolders Section-------------------------------------------------------------- -->
    <!-- Subfolders Section -->
    <!-- ------------------------------------------------------------------------------------------------------------------------- -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5>Subfolders</h5>
        </div>
        <div class="card-body">
            @if($folder->subfolders->isNotEmpty())
                <ul class="list-group">
                    @foreach($folder->subfolders as $subfolder)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://st3.depositphotos.com/1688079/14959/i/450/depositphotos_149595992-stock-photo-folder-icon-glassy-soft-green.jpg"
                                    class="rounded-circle img-fluid me-3" height='40' width='40'>
                                <div>
                                    <a href="{{ route('show', $subfolder->id) }}" class="text-dark">{{ $subfolder->name }}</a>
                                    <span class="text-muted small">{{ $subfolder->created_at->format('d M Y') }}</span>
                                    <!-- Delete Folder with SweetAlert -->
                                    <form method="POST" action="{{ route('destroy', $subfolder->id) }}" class="d-inline mx-2"
                                        id="delete-folder-{{ $subfolder->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-link p-0" style="border: none; background: none;"
                                            onclick="confirmDeleteFolder({{ $subfolder->id }})" title="Delete Folder">
                                            <i style="font-size: 24px; color:red" class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                    <!-- ---------------------------------------------------------------------------------------------------------------------------------------- -->
                                    <!-- ----------------------------------------------------------- {{-- Rename Folder --}} -->
                                    <!-- Rename Subfolder Button -->
                                    <a href="#" class="rename-subfolder-btn" data-subfolder-id="{{ $subfolder->id }}"
                                        data-subfolder-name="{{ $subfolder->name }}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Rename Subfolder Modal -->
                                    <div class="modal fade" id="renameSubfolderModal" tabindex="-1"
                                        aria-labelledby="renameSubfolderModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="renameSubfolderModalLabel">Rename Subfolder</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="renameSubfolderForm"
                                                        action="{{ route('updateFolder', $subfolder->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="form_type" value="rename">
                                                        <input type="hidden" name="id" id="subfolderId" value="">

                                                        <div class="mb-3">
                                                            <label for="subfolderName" class="form-label">Subfolder Name</label>
                                                            <input type="text" id="subfolderName" name="name"
                                                                class="form-control" placeholder="Enter new name" required>
                                                            <!-- Error display -->
                                                            <span class="text-danger" id="nameError"></span>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('name'))
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function () {
                                                var renameSubfolderModal = new bootstrap.Modal(document.getElementById('renameSubfolderModal'));
                                                renameSubfolderModal.show();

                                                // Show the validation error inside the modal
                                                document.getElementById('nameError').textContent = '{{ $errors->first('name') }}';
                                            });
                                        </script>
                                    @endif

                                    <script>


                                        // Show Rename Subfolder Modal with details prefilled
                                        document.querySelectorAll('.rename-subfolder-btn').forEach(button => {
                                            button.addEventListener('click', function () {
                                                const subfolderId = this.getAttribute('data-subfolder-id');
                                                const subfolderName = this.getAttribute('data-subfolder-name');

                                                // Set the values in the modal form
                                                document.getElementById('subfolderId').value = subfolderId;
                                                document.getElementById('subfolderName').value = subfolderName;

                                                // Update the form action dynamically
                                                const form = document.getElementById('renameSubfolderForm');
                                                form.action = form.action.replace('subfolderId', subfolderId);

                                                // Show the modal
                                                const renameSubfolderModal = new bootstrap.Modal(document.getElementById('renameSubfolderModal'));
                                                renameSubfolderModal.show();
                                            });
                                        });

                                        // Ensure the page reloads after the modal is closed
                                        document.getElementById('renameSubfolderModal').addEventListener('hidden.bs.modal', function () {
                                            location.reload(); // Refresh the page when the modal is closed
                                        });


                                    </script>

                                    <!-- ------------------------------------------------------------------------------------------------------------------------- -->

                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No subfolders available.</p>
            @endif
        </div>
    </div>
    <!-- ------------------------------------------------------------------------------------------------------------------------- -->
    <!-- Files Section -->
    <!-- ------------------------------------------------------------------------------------------------------------------------- -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5>Files</h5>
        </div>
        <div class="card-body">
            @if($folder->files->isNotEmpty())
                <ul class="list-group">
                    @foreach($folder->files as $file)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://img.freepik.com/premium-vector/vector-design-doc-icon-style_1134108-141048.jpg"
                                    class="rounded-circle img-fluid me-3" height='40' width='40'>
                                <div>
                                    <a href="{{ Storage::url($file->file_path) }}" class="text-dark"
                                        target="_blank">{{ basename($file->file_name) }}</a>
                                    <span class="text-muted small">{{ $file->created_at->format('d M Y') }}</span>
                                    <!-- Delete File with SweetAlert -->
                                    <form action="{{ route('deleteFile', $file->id) }}" method="POST" class="d-inline mx-2"
                                        id="delete-file-{{ $file->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-link p-0" style="border: none; background: none;"
                                            onclick="confirmDeleteFile({{ $file->id }})" title="Delete File">
                                            <i class="fa fa-trash" style="color: red;font-size: 24px; "></i>
                                        </button>
                                    </form>

                                    <!-- edit /Rename  -->

                                   <!-- Edit File Button -->
<!-- Edit Filename Icon -->
<a href="#" class="edit-file-btn" 
   data-file-id="{{ $file->id }}" 
   data-file-name="{{ $file->name }}" 
   data-bs-toggle="modal" 
   data-bs-target="#renameFileModal"
   onclick="setRenameModalData('{{ $file->id }}', '{{ $file->name }}')">
   <i class="fas fa-edit"></i>
</a>

<!-- Rename File Modal -->
<div class="modal fade" id="renameFileModal" tabindex="-1" aria-labelledby="renameFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="renameFileModalLabel">Rename File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="renameFileForm" action="{{ route('renameFile', 0) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="new_file_name" class="form-label">New File Name</label>
                        <input type="text" id="new_file_name" name="new_file_name" class="form-control" placeholder="Enter new file name..." required>
                        @if ($errors->has('new_file_name'))
                            <span class="text-danger">{{ $errors->first('new_file_name') }}</span>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Rename</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Handle Modal Behavior -->
<script>
function setRenameModalData(fileId, fileName) {
    // Set the file ID in the form action
    document.getElementById('renameFileForm').action = document.getElementById('renameFileForm').action.replace('0', fileId);
    
    // Set the current file name in the input field
    document.getElementById('new_file_name').value = fileName;
}
</script>



                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No files available.</p>
            @endif
        </div>

    </div>
    <!-- ------------------------------------------Show URL------------------------------------------------------------------------------- -->

    <!-- ------------------------------------------------------------------------------------------------------------------------- -->


    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5>URLs</h5>
        </div>
        <div class="card-body">
            @if($folder->urls->isNotEmpty())
                <ul class="list-group">
                    @foreach($folder->urls as $url)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="https://img.icons8.com/ios-filled/50/000000/link.png"
                                    class="rounded-circle img-fluid me-3" height='40' width='40'>
                                <div>
                                    <a href="{{ $url->url }}" class="text-dark" target="_blank">{{ $url->name }}</a>
                                    <span class="text-muted small">{{ $url->created_at->format('d M Y') }}</span>

                                    <!-- Delete URL with SweetAlert -->
                                    <form action="{{ route('deleteUrl', $url->id) }}" method="POST" class="d-inline mx-2"
                                        id="delete-url-{{ $url->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-link p-0" style="border: none; background: none;"
                                            onclick="confirmDeleteUrl({{ $url->id }})" title="Delete URL">
                                            <i class="fa fa-trash" style="color: red;font-size: 24px;"></i>
                                        </button>
                                    </form>

                                    <!-- Edit / Rename URL -->
                                    <a href="#" class="edit-url-btn" data-url-id="{{ $url->id }}"
                                        data-url-name="{{ $url->name }}" data-bs-toggle="modal" data-bs-target="#renameUrlModal"
                                        onclick="setRenameUrlModalDataaaa('{{ $url->name }}', '{{ $url->id }}')">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No URLs found.</p>
            @endif
        </div>
    </div>

    <!-- Rename URL Modal -->

    <div class="modal fade" id="renameUrlModal" tabindex="-1" aria-labelledby="renameUrlModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="renameUrlModalLabel">Rename URL</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="renameUrlForm" action="{{ route('renameUrl', 0) }}" method="POST">
                        <!-- Ensure this is correct -->
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="rename_url_id" name="url_id" value=""> <!-- Hidden field for URL ID -->
                        <div class="mb-3">
                            <label for="new_url_name" class="form-label">New URL Name</label>
                            <input type="text" id="new_url_name" name="new_url_name" class="form-control"
                                placeholder="Enter new URL name..." required>
                            @if ($errors->has('new_url_name'))
                                <span class="text-danger">{{ $errors->first('new_url_name') }}</span>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-warning">Rename</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('styles')
    <style>
        /* Styling as before */
    </style>
    @endsection
    <style>
        .modal-content {
            background-color: #f9f9f9;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .modal-header.bg-gradient-primary {
            background: linear-gradient(135deg, #007bff, #6c63ff);
            border-bottom: none;
        }

        input.form-control {
            border: 2px solid #007bff;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input.form-control:focus {
            border-color: #6c63ff;
            box-shadow: 0 0 5px rgba(108, 99, 255, 0.5);
        }

        button.btn {
            transition: background-color 0.3s ease, transform 0.2s;
        }

        button.btn:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-close-white {
            filter: invert(1);
        }
    </style>

    @section('scripts')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Check if SweetAlert is loading
        console.log('SweetAlert loaded:', typeof Swal !== 'undefined');

        function confirmDeleteFolder(folderId) {
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
                    document.getElementById(`delete-folder-${folderId}`).submit();
                }
            });
        }

        function confirmDeleteFile(fileId) {
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
                    document.getElementById(`delete-file-${fileId}`).submit();
                }
            });
        }
        function confirmDeleteUrl(urlId) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-url-' + urlId).submit();
                }
            });
        }

        // const createFolderModal = document.getElementById('createFolderModal');
        // createFolderModal.addEventListener('show.bs.modal', function (event) {
        //     const button = event.relatedTarget; // Button that triggered the modal
        //     const parentId = button.getAttribute('data-parent-id'); // Extract parent_id from data attribute
        //     const modalParentIdInput = document.getElementById('modalParentId'); // Hidden input field in the modal
        //     modalParentIdInput.value = parentId; // Set the value of hidden input
        // });

        // ----------------------------------------------Renam /Edit-----------------------------------------------------------



        function setRenameUrlModalDataaaa(urlName, urlId) {
            document.getElementById('new_url_name').value = urlName; // Set the current URL name
            document.getElementById('rename_url_id').value = urlId; // Set the URL ID
            document.getElementById('renameUrlForm').action = "{{ url('urls') }}/" + urlId; // Set the action for the form
        }


    </script>
    @endsection