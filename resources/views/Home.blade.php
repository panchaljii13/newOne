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
                                    @foreach($folders as $folder)
                                        @if(is_null($folder->parent_id) && $folder->is_public) {{-- Ensure it's a parent folder and public --}}
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <a href="#" id="navbarDropdown" aria-expanded="false">
                                                            <img src="https://st3.depositphotos.com/1688079/14959/i/450/depositphotos_149595992-stock-photo-folder-icon-glassy-soft-green.jpg" class="rounded-circle img-fluid" height='25' width='25'>
                                                        </a>
                                                        {{ $folder->name }} 
                                                        <small class="text-muted">by {{ $folder->user->name }}</small>
                                                          {{-- Download Folder --}}
                                            @if (is_null($folder->parent_id))
                                                <a href="{{ route('download', $folder->id) }}" class="btn btn-link p-2"
                                                    title="Download Folder">
                                                    <i class="fa fa-download ml-5" style="font-size: 24px; color: #0072ff;"></i>
                                                </a>
                                            @endif
                                                    </div>
                                                    <button class="btn btn-view btn-sm mx-1 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $folder->id }}" aria-expanded="false" aria-controls="collapse-{{ $folder->id }}">
                                                        <i class="fas fa-folder-open"></i> View
                                                    </button>  
                                                </div>

                                                {{-- Collapsible section for files and subfolders --}}
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
                                                                            
                                                                            

                                                                            {{-- Collapsible section for subfolder files --}}
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
@endsection
