@extends('Layout')
@section('title', 'UserLogin')
@section('content')
<section class="h-100 h-custom" style="background-color: #8fc4b7;">
    <div class="container py-2 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-7 col-xl-6">
                <div class="card rounded-3">
                    <img src="https://cdn.pixabay.com/photo/2024/04/19/12/13/ai-generated-8706226_640.png"
                         class="w-100" style="border-top-left-radius: .3rem; border-top-right-radius: .3rem;"
                         alt="Sample photo">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 px-md-2">  LogIn Your Account</h3>

                        <form action="{{ route('UserLogin') }}" method="POST"> <!-- Use correct route name -->
                            @csrf <!-- CSRF token for form security -->

                            <!-- Email Field -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                       placeholder="Enter Email" required />
                            </div>

                            <!-- Password Field -->
                            <div data-mdb-input-init class="form-outline mb-4">
                                <label class="form-label" for="password">Password</label>
                                <input type="password" id="password" name="password" class="form-control"
                                       placeholder="Enter Password" required />
                            </div>

                            <button type="submit" style="width: 100px; height: 40px; padding: 0; line-height: 40px; border: none;" class="btn btn-success btn-lg mb-1">Submit</button>

                            <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="{{ route('UserRegistration') }}"
                                class="link-danger">Register</a></p>
                        </form>

                        <!-- Display validation errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
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
@endsection
