@extends('Layout')

@section('title', 'Create Folder')

@section('content')
<section class="h-100 h-custom" style="background-color: #f8f9fa;"> <!-- Set a light background color -->
    <div class="container py-2 h-80">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-6 col-xl-6">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="mb-4">Create Folder</h3>

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
                       
                        <form action="{{ route('store') }}" method="POST">
                            @csrf
                         
                            <input type="hidden" name="parent_id" value="{{ $parentId }}">

                            <div class="form-outline mb-4">
                                <label class="form-label" for="name">Folder Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                                
                                {{-- Display specific error for the name field --}}
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>

                            
                            <button type="submit" class="btn btn-success btn-lg" style="width: 100px; height: 40px; padding: 0; line-height: 40px; border: none;">Create</button>
                            <a href="{{ route('indexFolder') }}" class="btn btn-primary btn-lg" style="width: 100px; height: 40px; padding: 0; line-height: 40px; border: none;">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
