@extends('Layout')

@section('title', 'Folders')

@section('content')
@include('include.Header')

<section class="h-100" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
    <div class="container py-5 h-100">
        <div class="row justify-content-center align-items-start h-100">
            <div class="col-lg-10 col-xl-10">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-body p-9">
                        <h3 class="text-center mb-4 text-black">Document Management System</h3>

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

                        <a class="btn btn-gradient mb-3" href="{{ route('create') }}" style="width: 100%; max-width: 200px;">
                            <i class="fas fa-folder-plus"></i> Create Folder
                        </a>

                        <div id="folder-list">
                            <ul class="list-group">
                                @foreach($folders as $folder)
                                    <li class="list-group-item">
                                        @include('partials', ['folder' => $folder]) {{-- Use recursive partial for folder --}}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Infinite Scroll Loader -->
                        <!-- <div id="infinite-scroll-loader" class="text-center py-3">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div> -->
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

    /* Button Styles */
    .btn {
        width: 66px; height: 40px; padding: 0; 
    }
    .btn-gradient {
        background: linear-gradient(45deg, #6a11cb, #2575fc);
        color: #fff;
        border: none;
        border-radius: 25px;
        padding: 10px 20px;
        transition: all 0.3s;
    }

    .btn-gradient:hover {
        background: linear-gradient(45deg, #2575fc, #6a11cb);
        transform: translateY(-2px);
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

    .btn-upload {
        background-color: #00c4ff;
        color: white;
        border-radius: 20px;
        transition: transform 0.3s;
    }

    .btn-upload:hover {
        background-color: #009ecc;
        transform: translateY(-1px);
    }

    .btn-subfolder {
        background-color: #ffbb00;
        color: white;
        border-radius: 20px;
        transition: transform 0.3s;
    }

    .btn-subfolder:hover {
        background-color: #cc9600;
        transform: translateY(-1px);
    }

    .btn-delete {
        background-color: #ff4d4d;
        color: white;
        border-radius: 20px;
        transition: transform 0.3s;
    }

    .btn-delete:hover {
        background-color: #cc0000;
        transform: translateY(-1px);
    }

    /* Responsive Design for Buttons */
    @media (max-width: 768px) {
        .d-flex {
            flex-direction: column;
        }
    }

    .collapse {
        transition: max-height 0.4s ease-out;
    }
</style>
@endsection
