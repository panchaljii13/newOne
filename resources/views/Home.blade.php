@extends('Layout')
@section('title', 'Home')
@section('content')

@include('include.Header')
<h1>welcome</h1>
<h1></h1>
<p>hello</p>
<h1>welcome</h1>
@auth
<h1>{{auth()->user()->name}}</h1>
@endauth

<div>
<section class="h-100" style="background: linear-gradient(to right, #6a11cb, #2575fc);">
    <div class="container py-5 h-100">
        <div class="row justify-content-center align-items-start h-100">
            <div class="col-lg-10 col-xl-10">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-body p-9">
                        <h3 class="text-center mb-4 text-black">Public Folders</h3>

                        {{-- Check if there are folders --}}
                        @if($folders->isEmpty())
                            <p>No public folders available.</p>
                        @else
                            <div id="folder-list">
                                <ul class="list-group">
                                    @foreach($folders as $folder)
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-folder" style="color: #0072ff;"></i> 
                                                    {{ $folder->name }} 
                                                    <small class="text-muted">by {{ $folder->user->name }}</small>
                                                </div>
                                                <button class="btn btn-view btn-sm mx-1 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $folder->id }}" aria-expanded="false" aria-controls="collapse-{{ $folder->id }}">
                                                    <i class="fas fa-folder-open"></i> View
                                                </button>
                                            </div>

                                            {{-- Collapsible section for files --}}
                                            <div class="collapse mt-3" id="collapse-{{ $folder->id }}">
                                                <ul class="list-unstyled ms-4">
                                                    {{-- Display Files in Folder --}}
                                                    @if($folder->files->isNotEmpty())
                                                        <li><strong>Files:</strong>
                                                            <ul class="list-unstyled">
                                                                @foreach($folder->files as $file)
                                                                    <li>
                                                                        <i class="fas fa-file" style="color: #555;"></i>
                                                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_path) }}</a>
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