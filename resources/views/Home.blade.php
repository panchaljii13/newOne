@extends('Layout')
@section('title', 'Homeindex')
@section('content')

@include('include.Header')

<div>
<section class="h-100" style="min-height: 100vh;">
    <div class="py-5">
        <div class="row h-100">
            <div class="col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-body p-9">
                        <h3 class="text-center mb-4 text-black">Welcome to Your Document Management System! This is Public Folders</h3>

                        {{-- Check if there are public parent folders --}}
                        @if($folders->isEmpty())
                            <p>No public folders available.</p>
                        @else
                            <div id="folder-list">
                                <ul class="list-group">
                                    {{-- Display Public Parent Folders --}}
                                    @foreach($folders as $folder)
                                        @if(is_null($folder->parent_id) && $folder->is_public)
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <a href="#" id="navbarDropdown" aria-expanded="false">
                                                            <img src="https://st3.depositphotos.com/1688079/14959/i/450/depositphotos_149595992-stock-photo-folder-icon-glassy-soft-green.jpg" class="rounded-circle img-fluid" height='25' width='25'>
                                                        </a>
                                                        {{ $folder->name }} 
                                                        <small class="text-muted">by {{ $folder->user->name }}</small>
                                                        <a href="#" class="btn btn-link p-2 download-folder" data-folder-id="{{ $folder->id }}" title="Download Folder">
                                                            <i class="fa fa-download ml-5" style="font-size: 24px; color: #0072ff;"></i>
                                                        </a>
                                                    </div>
                                                    <button class="btn btn-view btn-sm mx-1 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $folder->id }}" aria-expanded="false" aria-controls="collapse-{{ $folder->id }}">
                                                        <i class="fas fa-folder-open"></i> View
                                                    </button>  
                                                </div>

                                                {{-- Collapsible section for files, subfolders, and URLs --}}
                                                <div class="collapse mt-3" id="collapse-{{ $folder->id }}">
                                                    <ul class="list-unstyled ms-4">
                                                        {{-- Display Subfolders --}}
                                                        @if($folder->subfolders->isNotEmpty())
                                                            <li><strong>Subfolders:</strong>
                                                                <ul class="list-unstyled">
                                                                    @foreach($folder->subfolders as $subfolder)
                                                                        <li>
                                                                            <a href="#" id="navbarDropdown" aria-expanded="false">
                                                                                <img src="https://st3.depositphotos.com/1688079/14959/i/450/depositphotos_149595992-stock-photo-folder-icon-glassy-soft-green.jpg" class="rounded-circle img-fluid" height='20' width='20'>
                                                                            </a>
                                                                            {{ $subfolder->name }} 
                                                                            <small class="text-muted">by {{ $subfolder->user->name }}</small>
                                                                            <button class="btn btn-view btn-sm mx-1 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-subfolder-{{ $subfolder->id }}" aria-expanded="false" aria-controls="collapse-subfolder-{{ $subfolder->id }}">
                                                                                <i class="fas fa-folder-open"></i> View
                                                                            </button>
                                                                            
                                                                            {{-- Collapsible section for subfolder files and URLs --}}
                                                                            <div class="collapse mt-2" id="collapse-subfolder-{{ $subfolder->id }}">
                                                                                <ul class="list-unstyled ms-4">
                                                                                    {{-- Display Files in Subfolder --}}
                                                                                    @if($subfolder->files->isNotEmpty())
                                                                                        <li><strong>Files:</strong>
                                                                                            <ul class="list-unstyled">
                                                                                                @foreach($subfolder->files as $file)
                                                                                                    <li>
                                                                                                        <i class="fas fa-file" style="color: #555;"></i>
                                                                                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_name) }}</a>
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </li>
                                                                                    @else
                                                                                        <li><em>No files available in this subfolder.</em></li>
                                                                                    @endif

                                                                                    {{-- Display URLs in Subfolder --}}
                                                                                    @if($subfolder->urls->isNotEmpty())
                                                                                        <li><strong>URLs:</strong>
                                                                                            <ul class="list-unstyled">
                                                                                                @foreach($subfolder->urls as $url)
                                                                                                    <li>
                                                                                                        <i class="fas fa-link" style="color: #555;"></i>
                                                                                                        <a href="{{ $url->link }}" target="_blank">{{ $url->name }}</a>
                                                                                                        <small class="text-muted">by {{ $url->user->name }}</small>
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </li>
                                                                                    @else
                                                                                        <li><em>No URLs available in this subfolder.</em></li>
                                                                                    @endif
                                                                                </ul>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @else
                                                            <li><em>No subfolders available in this folder.</em></li>
                                                        @endif

                                                        {{-- Display Files in Parent Folder --}}
                                                        @if($folder->files->isNotEmpty())
                                                            <li><strong>Files:</strong>
                                                                <ul class="list-unstyled">
                                                                    @foreach($folder->files as $file)
                                                                        <li>
                                                                            <i class="fas fa-file" style="color: #555;"></i>
                                                                            <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_name) }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @else
                                                            <li><em>No files available in this folder.</em></li>
                                                        @endif

                                                        {{-- Display URLs in Parent Folder --}}
                                                        @if($folder->urls->isNotEmpty())
                                                            <li><strong>URLs:</strong>
                                                                <ul class="list-unstyled">
                                                                    @foreach($folder->urls as $url)
                                                                        <li>
                                                                            <i class="fas fa-link" style="color: #555;"></i>
                                                                            <a href="{{ $url->url }}" target="_blank">{{ $url->name }}</a>
                                                                            <small class="text-muted">by {{ $url->user->name }}</small>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </li>
                                                        @else
                                                            <li><em>No URLs available in this folder.</em></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .h-100 {
        height: 100vh;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        background-color: rgba(255, 255, 255, 0.9);
    }

    .btn-view {
        background-color: #0072ff;
        color: white;
        border-radius: 20px;
        transition: transform 0.3s;
    }

    .btn-view:hover {
        background-color: #005bb5;
        transform: translateY(-1px);
    }
</style>
</div>

<!-- Include SweetAlert2 and Axios -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    // Add click event listener to download links
    document.querySelectorAll('.download-folder').forEach(function(link) {
        link.addEventListener('click', function(e) {
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
</script>
@endsection
