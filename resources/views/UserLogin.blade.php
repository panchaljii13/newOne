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
                        <h3 class="mb-4 pb-2 pb-md-0 mb-md-5 px-md-2">LogIn Your Account</h3>

                        <form id="loginForm">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Form ko submit hone se rokta hai

        // Get email and password values
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Send Axios POST request
        axios.post('/UserLogin', {
            email: email,
            password: password,
            _token: "{{ csrf_token() }}" // Laravel CSRF token
        })
        .then(response => {
            const token = response.data.token;
            localStorage.setItem('auth_token', token); // Token ko localStorage mein save karta hai
            sessionStorage.setItem('auth_token', token); 
            window.location.href = '/home'; // Redirect after login
            // SweetAlert2 success alert
            Swal.fire({
                title: 'Success!',
                text: 'Login successful!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Redirect user after SweetAlert closes
               
            });
        })
        .catch(error => {
            console.log(error.response.data);

            // SweetAlert2 error alert
            Swal.fire({
                title: 'Error!',
                text: 'Login failed. Please check your credentials.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    });
</script>

</section>
@endsection
