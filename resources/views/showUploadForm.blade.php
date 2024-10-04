@extends('Layout')

@section('title', 'Upload File')

@section('content')
<div class="container mt-5">
    <h3>Upload File to Folder: {{ $folder->name }}</h3>

    {{-- Display any validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('file.upload', $folder->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="file_name" class="form-label">File Name</label>
            <input type="text" name="file_name" id="file_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="file" class="form-label">Select File</label>
            <input type="file" name="file" id="file" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
@endsection
