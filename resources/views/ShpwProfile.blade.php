@extends('Layout')

@section('title', 'Myprofile')

@section('content')

<section class="h-100" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h3 class="text-center mb-4 text-primary">Profile Information</h3>

                    <div class="text-center mb-4">
                        <img src="data:image/jpeg;base64,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" alt="Profile Picture" class="rounded-circle mb-3" />
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary" onclick="window.history.back();">Back</button>
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
        background-color: rgba(255, 255, 255, 0.9);
    }

    .btn-primary {
        background-color: #0072ff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #005bb5;
    }
</style>

@endsection
