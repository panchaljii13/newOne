@extends('Layout')

@section('title', 'uplodFile')

@section('content')
<section class="h-100 h-custom">
    <div class="container py-2 h-80">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-6 col-xl-6">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="mb-4">Upload File</h3>

                        {{-- Display any success messages --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
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
                       
                        <form action="{{ route('uploadFile', $folder->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-outline mb-4">
                                <label class="form-label" for="file">Choose File</label>
                                <input type="file" id="file" name="file" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg" style="width: 100px;">Upload</button>
                            <a href="{{ route('indexFolder') }}" class="btn btn-primary btn-lg" style="width: 100px;">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
