@extends('Layout')

@section('title', 'Folders')

@section('content')
<section class="h-100" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
    <div class="container py-5 h-100">
        <div class="row justify-content-center align-items-start h-100">
            <div class="col-lg-10 col-xl-10">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4 text-black">Document Management System</h3>

                        <a class="btn btn-light mb-3" style="width: 150px; height: 50px; border-radius: 25px;" href="{{ route('create') }}">
                            <i class="fas fa-folder-plus"></i> Create Folder
                        </a>

                        <ul class="list-group">
                            @foreach($folders as $folder)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-folder" style="color: #0072ff;"></i> {{ $folder->name }}
                                    </div>

                                    <div class="d-flex">
                                        <!-- Button for subfolders dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm mx-1 dropdown-toggle" style="width: 120px; height: 40px;" type="button" id="dropdownSubfolder-{{ $folder->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                Subfolders
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownSubfolder-{{ $folder->id }}">
                                                @if($folder->subfolders->isEmpty())
                                                    <li class="dropdown-item">No Subfolders</li>
                                                @else
                                                    @foreach($folder->subfolders as $subfolder)
                                                        <li class="dropdown-item">
                                                            <i class="fas fa-folder" style="color: #0072ff;"></i> {{ $subfolder->name }}
                                                            <!-- Show files inside subfolder -->
                                                            <ul class="list-unstyled ms-3">
                                                                @foreach($subfolder->files as $file)
                                                                    <li><i class="fas fa-file" style="color: #555;"></i> {{ $file->name }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>

                                        <!-- Button to upload file -->
                                        <a class="btn btn-info btn-sm mx-1" href="#" style="width: 100px; height: 40px;">Upload File</a>

                                        <!-- Button to add subfolder -->
                                        <a class="btn btn-warning btn-sm mx-1" href="{{ route('create', ['parentId' => $folder->id]) }}" style="width: 120px; height: 40px;">
    <i class="fas fa-folder-plus"></i> Add Subfolder
</a>

                                        <!-- Delete folder button -->
                                        <form method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" style="width: 100px; height: 40px;">Delete</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Add custom styles for improved aesthetics --}}
<style>
    .h-100 {
        height: 100vh;
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        background-color: rgba(255, 255, 255, 0.9);
    }

    .list-group-item {
        transition: background-color 0.3s;
    }

    .list-group-item:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    .btn {
        transition: background-color 0.3s, color 0.3s, transform 0.2s;
    }

    .btn-danger {
        transition: background-color 0.3s, transform 0.2s;
    }

    .btn-danger:hover {
        background-color: #dc3545;
        transform: translateY(-1px);
    }

    .dropdown-menu {
        max-height: 200px;
        overflow-y: auto;
    }

    @media (max-width: 768px) {
        .col-lg-10 {
            width: 90%;
        }
    }
</style>
@endsection
