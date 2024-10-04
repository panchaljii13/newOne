<div class="d-flex justify-content-between align-items-center  p-2">
    <div class="d-flex align-items-center">
        <a href="#" id="navbarDropdown" aria-expanded="false">
            <img src="https://w7.pngwing.com/pngs/763/637/png-transparent-directory-icon-folder-miscellaneous-angle-rectangle-thumbnail.png"
                class="rounded-circle img-fluid" height='25' width='25'>
        </a>
        <a href="#collapse-{{ $folder->id }}" data-bs-toggle="collapse" aria-expanded="false"
            aria-controls="collapse-{{ $folder->id }}" class="text-decoration-none text-dark ms-2">
            {{ $folder->name }} <span class="text-muted small">(Created:
                {{ $folder->created_at->format('d M Y') }})</span>
        </a>
    </div>

    <div class="d-flex flex-wrap justify-content-end">
        <div>
            @if (is_null($folder->parent_id)) <!-- Check if the folder is a parent folder -->
                @if($folder->is_public)
                    <span class="badge bg-success ms-2" title="Public">🔓 Public</span>
                @else
                    <span class="badge bg-secondary ms-2" title="Private">🔒 Private</span>
                @endif
            @endif
        </div>

        <!-- Upload File -->
        <form action="{{ route('uploadFile', $folder->id) }}" method="POST" enctype="multipart/form-data"
            class="d-inline mx-1 mb-1">
            @csrf
            <input type="file" name="file" required>
            <button type="submit" class="btn btn-primary btn-sm" style="width: 100px;">Upload</button>
        </form>

        <!-- Add Subfolder Button -->
        <a class="btn btn-subfolder btn-sm p-2" style="width: 100px;"
            href="{{ route('create', ['parentId' => $folder->id]) }}">
            <i class="fas fa-folder-plus"></i> Subfolder
        </a>

        <!-- Rename Folder -->
        <a href="{{ route('editFolder', $folder->id) }}" class="btn btn-info btn-sm p-2">Edit</a>

        <!-- Toggle Public/Private only for parent folders -->
        @if (is_null($folder->parent_id))
            <form method="POST" action="{{ route('togglePublic', $folder->id) }}" class="d-inline mx-1 mb-1"
                onsubmit="return confirm('Are you sure you want to change the visibility of this folder?');">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-link p-0" style="border: none; background: none;">
                    <i class="{{ $folder->is_public ? 'fas fa-unlock-alt' : 'fas fa-lock' }}"
                        style="font-size: 24px; color: {{ $folder->is_public ? 'green' : 'red' }};"></i>
                </button>
            </form>
        @endif

        <!-- Delete Folder Button -->
        <form method="POST" action="{{ route('destroy', $folder->id) }}" class="d-inline mx-1 mb-1"
            onsubmit="return confirm('Are you sure you want to delete this folder?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-link p-0" style="border: none; background: none;"
                title="Delete Folder">
                <i style="font-size:24px; color:red" class="fa fa-trash"></i>
            </button>
        </form>

        <!-- Download Folder as ZIP - Show only for parent folders -->
        @if (is_null($folder->parent_id)) <!-- Check if the folder is a parent folder -->
            <a href="{{ route('download', $folder->id) }}" class="btn btn-link p-2" style="text-decoration: none;"
                title="Download Folder">
                <i class="fa fa-download" style="font-size: 24px; color: #0072ff;"></i>
            </a>
        @endif
    </div>
</div>

<!-- Collapsible Subfolders and Files -->
<div class="collapse mt-3" id="collapse-{{ $folder->id }}">
    <ul class="list-unstyled ms-4">
        <!-- Display Subfolders Recursively -->
        @if($folder->subfolders->isNotEmpty())
            <li>
                <strong>Subfolders:</strong>
                <ul class="list-unstyled">
                    @foreach($folder->subfolders as $subfolder)
                        @if ($subfolder->user_id === auth()->id()) <!-- Check if the subfolder belongs to the current user -->
                            <li class="list-group-item">
                                @include('partials', ['folder' => $subfolder]) {{-- Recursively include subfolders --}}
                            </li>
                            <div>
                                <span class="text-muted small">(Created: {{ $subfolder->created_at->format('d M Y') }})</span>
                            </div>
                        @endif
                    @endforeach
                </ul>
            </li>
        @else
            <li>
                <strong>Subfolders:</strong>
                <p class="text-muted small">No subfolders available.</p>
            </li>
        @endif

        <!-- Display Files in Folder -->
        @if($folder->files->isNotEmpty())
            <li>
                <strong>Files:</strong>
                <ul class="list-styled">
                    @foreach($folder->files as $file)
                        @if ($file->user_id === auth()->id()) <!-- Check if the file belongs to the current user -->
                            <li>
                                <i class="fas fa-file" style="color: #555;"></i>
                                <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_name) }}</a>
                                <!-- File delete button -->
                                <form action="{{ route('deleteFile', $file->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="border: none; background: none;">
                                        <i class="fa fa-trash" style="color: red;"></i>
                                    </button>
                                </form>
                                <div>
                                    <span class="text-muted small">(Uploaded: {{ $file->created_at->format('d M Y') }})</span>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @else
            <li>
                <strong>Files:</strong>
                <p class="text-muted small">No files available.</p>
            </li>
        @endif
    </ul>
</div>