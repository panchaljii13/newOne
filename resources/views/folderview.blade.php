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
    <button class="btn btn-dark mb-3" onclick="window.history.back();"><- Back</button>
            <!-- Action buttons -->
            <div class="d-flex justify-content-between mb-4">
                <a class="btn btn-warning" href="{{ route('create', ['parentId' => $folder->id]) }}">
                    <i class="fas fa-folder-plus"></i> Create Subfolder
                </a>

                <div class="d-flex">
                    <!-- Upload File -->
                    <form action="{{ route('uploadFile', $folder->id) }}" method="POST" enctype="multipart/form-data"
                        class="me-2">
                        @csrf
                        <input type="file" name="file" class="form-control-sm me-2" required>
                        <button type="submit" class="btn btn-success">Upload</button>
                    </form>



                </div>
            </div>

            <!-- Subfolders Section -->
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
                                            <a href="{{ route('show', $subfolder->id) }}"
                                                class="text-dark">{{ $subfolder->name }}</a>
                                            <span class="text-muted small">{{ $subfolder->created_at->format('d M Y') }}</span>
                                            {{-- Delete Folder --}}
                                            <form method="POST" action="{{ route('destroy', $subfolder->id) }}"
                                                class="d-inline mx-2"
                                                onsubmit="return confirm('Are you sure you want to delete this folder?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 "
                                                    style="border: none; background: none;" title="Delete Folder">
                                                    <i style="font-size: 24px; color:red" class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            <a href="{{ route('editFolder', $subfolder->id) }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <!-- <a href="{{ route('editFolder', $subfolder->id) }}">
                                                <i class="fas fa-edit"></i>
                                            </a> -->
                                </li>

                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No subfolders available.</p>
                    @endif
                </div>
            </div>

            <!-- Files Section -->
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
                                            <!-- File delete button -->

                                            <form action="{{ route('deleteFile', $file->id) }}" method="POST"
                                                class="d-inline mx-2"
                                                onsubmit="return confirm('Are you sure you want to delete this file?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    style="border: none; background: none;">
                                                    <i class="fa fa-trash" style="color: red;font-size: 24px; "></i>
                                                </button>
                                            </form>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No files available.</p>
                    @endif
                </div>
            </div>


</div>
@endsection

@section('styles')
<style>
    body {
        background-color: #f8f9fa;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-dark {
        background-color: #343a40;
        border-color: #343a40;
    }

    .card {
        border-radius: 10px;
        border: none;
    }

    .card-header {
        border-radius: 10px 10px 0 0;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #dee2e6;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endsection