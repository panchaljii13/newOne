@extends('Layout')

@section('title', 'Edit Folder')

@section('content')
<section class="h-100 h-custom">
    <div class="container py-2 h-80">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-6 col-xl-6">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="mb-4">Edit Folder Name</h3>

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

                        <form action="{{ route('updateFolder', $folder->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-outline mb-4">
        <label class="form-label" for="name">Folder Name</label>
        <input type="text" id="name" name="name" class="form-control" value="{{ $folder->name }}" required>
    </div>


    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('indexFolder') }}" class="btn btn-primary">Cancel</a>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
