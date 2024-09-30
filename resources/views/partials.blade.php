<div class="d-flex justify-content-between align-items-center">
    <div>
        <i class="fas fa-folder" style="color: #0072ff;"></i> {{ $folder->name }}
        @if($folder->is_public)
            <span class="badge bg-success">Public</span>
        @else
            <span class="badge bg-secondary">Private</span>
        @endif
    </div>
    <div class="d-flex flex-wrap justify-content-end">
        <!-- Dropdown to Expand Subfolders -->
        <button class="btn btn-view btn-sm mx-1 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $folder->id }}" aria-expanded="false" aria-controls="collapse-{{ $folder->id }}">
            <i class="fas fa-folder-open"></i> View
        </button>

        <!-- Upload File -->
        <form action="{{ route('uploadFile', $folder->id) }}" method="POST" enctype="multipart/form-data" class="d-inline mx-1 mb-1">
            @csrf
            <input type="file" name="file" required>
            <button type="submit" class="btn btn-upload btn-sm" style="width: 100px;">Upload</button>
        </form>

        <!-- Add Subfolder Button -->
        <a class="btn btn-subfolder btn-sm mx-1 mb-1" href="{{ route('create', ['parentId' => $folder->id]) }}">
            <i class="fas fa-folder-plus"></i> Subfolder
        </a>
        
        <!-- Rename Folder -->
        <a href="{{ route('editFolder', $folder->id) }}" class="btn btn-warning">Edit</a> 

        <!-- Toggle Public/Private -->
        <form method="POST" action="{{ route('togglePublic', $folder->id) }}" class="d-inline mx-1 mb-1">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-secondary btn-sm" style="width: 100px;">
                {{ $folder->is_public ? 'Make Private' : 'Make Public' }}
            </button>
        </form>

        <!-- Delete Folder Button -->
        <form method="POST" action="{{ route('destroy', $folder->id) }}" class="d-inline mx-1 mb-1">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-delete btn-sm" style="width: 100px;">Delete</button>
        </form>
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
                        <li class="list-group-item">
                            @include('partials', ['folder' => $subfolder]) {{-- Recursively include subfolders --}}
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

        <!-- Display Files in Folder -->
        @if($folder->files->isNotEmpty())
            <li>
                <strong>Files:</strong>
                <ul class="list-unstyled">
                    @foreach($folder->files as $file)
                        <li>
                            <i class="fas fa-file" style="color: #555;"></i>
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_path) }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    </ul>
</div>
