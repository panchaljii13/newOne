@extends('Layout')

@section('title', 'Document Management System')

@section('content')
@include('include.Header')

<section>
    <div class="py-5 ">
        <div class="row ">
            <div class="col-lg-12 col-xl-12">
                <div class="card ">
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

                        {{-- Create Folder Button --}}
                        <a class="btn btn-gradient mb-3" href="{{ route('create') }}"
                            style="width: 100%; max-width: 200px;">
                            <i class="fas fa-folder-plus" > </i> Create Folder
                        </a>

        
 

                        <div id="folder-list" class="table-responsive">
                            <ul class="list-group list-group-flush">
                                @foreach($folders as $folder)
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center bg-white shadow-sm rounded-3 mb-3 p-3">
                                        {{-- Folder Icon and Name --}}
                                        <div class="d-flex align-items-center">
                                            <img src="https://st3.depositphotos.com/1688079/14959/i/450/depositphotos_149595992-stock-photo-folder-icon-glassy-soft-green.jpg"
                                                class="rounded-circle img-fluid me-3" height='40' width='40'>
                                            <div>
                                                <a href="{{ route('show', $folder->id) }}"
                                                    class="text-dark fw-bold">{{ $folder->name }}</a>
                                                <span class="text-muted small d-block mr-3" >Created:
                                                    {{ $folder->created_at->format('d M Y') }}</span>
                                            </div>
                                        </div>

                                        {{-- Folder Actions --}}
                                        <div class=" flex-wrap align-items-center mr-5">
                                            {{-- Public/Private Badge --}}
                                            <!-- @if (is_null($folder->parent_id))
                                                <span
                                                    class="badge {{ $folder->is_public ? 'bg-success' : 'bg-secondary' }} ms-2">
                                                    {{ $folder->is_public ? '🔓 Public' : '🔒 Private' }}
                                                </span>
                                            @endif -->

                                            {{-- Upload File --}}
                                            <!-- <form action="{{ route('uploadFile', $folder->id) }}" method="POST" enctype="multipart/form-data"
                                                      class="d-inline mx-2">
                                                    @csrf
                                                    <input type="file" name="file" class="form-control-sm me-2" required>
                                                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                                </form> -->

                                            {{-- Add Subfolder --}}
                                            <!-- <a class="btn btn-subfolder btn-sm p-2 me-2" href="{{ route('create', ['parentId' => $folder->id]) }}">
                                                    <i class="fas fa-folder-plus"></i> Subfolder
                                                </a> -->

                                            {{-- Rename Folder --}}
                                            <a href="{{ route('editFolder', $folder->id) }}">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Toggle Public/Private --}}
                                            @if (is_null($folder->parent_id))
                                                <form method="POST" action="{{ route('togglePublic', $folder->id) }}"
                                                    class="d-inline mx-2"
                                                    onsubmit="return confirm('Are you sure you want to change the visibility of this folder?');">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-link p-0"
                                                        style="border: none; background: none;">
                                                        <i class="{{ $folder->is_public ? 'fas fa-unlock-alt' : 'fas fa-lock' }}"
                                                            style="font-size: 24px; color: {{ $folder->is_public ? 'green' : 'red' }};"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Delete Folder --}}
                                            <form method="POST" action="{{ route('destroy', $folder->id) }}"
                                                class="d-inline mx-2"
                                                onsubmit="return confirm('Are you sure you want to delete this folder?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0"
                                                    style="border: none; background: none;" title="Delete Folder">
                                                    <i style="font-size: 24px; color:red" class="fa fa-trash"></i>
                                                </button>
                                            </form>

                                            {{-- Download Folder --}}
                                            @if (is_null($folder->parent_id))
                                                <a href="{{ route('download', $folder->id) }}" class="btn btn-link p-2"
                                                    title="Download Folder">
                                                    <i class="fa fa-download" style="font-size: 24px; color: #0072ff;"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
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

    .btn-subfolder {
        background-color: #ffc107;
        color: white;
        border-radius: 25px;
    }

    .btn-subfolder:hover {
        background-color: #e0a800;
    }

    .list-group-item {
    }

    .list-group-item:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .btn-link, .fa {
    transition: none;
}
/* Prevent size changes on buttons and icons */
.btn, .btn-link, .fa {
    transform: none !important;
    transition: none !important;
    font-size: inherit !important;
}


.btn:active, .btn:focus, .fa:active, .fa:focus {
    transform: none !important;
    font-size: inherit !important;
}
</style>
@endsection