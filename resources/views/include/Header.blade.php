<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light">
  <!-- Container wrapper -->
  <div class="container-fluid">
    <!-- Navbar brand -->
    <a class="navbar-brand" href="#">
      <img src="https://cdn.worldvectorlogo.com/logos/dms-3.svg" height="45" alt="" loading="lazy" />
    </a>

    <!-- Toggle button -->
    <button class="navbar-toggler" style="width: 100px; height: 40px; padding: 0; line-height: 40px; border: none;"
      type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Collapsible wrapper -->
    <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
      <!-- Left links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="{{ route('Home') }}">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{ route('indexFolder') }}">Documents</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{ route('downloadHistory') }}">Downloaded</a>
        </li>
      </ul>

      <!-- Right links -->
      <ul class="navbar-nav mb-2 mb-lg-0">

        <!-- Navbar dropdown -->
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
   aria-expanded="false">

            <img src="https://c8.alamy.com/comp/TC2FPE/young-man-avatar-cartoon-character-profile-picture-TC2FPE.jpg"
              class="rounded-circle img-fluid" height='25' width='25'>
          </a>
          <!-- Dropdown menu -->
          <ul class="dropdown-menu dropdown-menu-end p-1" aria-labelledby="navbarDropdown">
            <li class="my-2 d-flex align-items-center"><img
                src="https://c8.alamy.com/comp/TC2FPE/young-man-avatar-cartoon-character-profile-picture-TC2FPE.jpg"
                class="rounded-circle img-fluid me-1" height='25' width='25'><span> {{ auth()->user()->name }}</span></li>

            <li>
              <hr class="dropdown-divider" />
            </li>
            <li><a class="dropdown-item" href="ShpwProfile">Your Account</a></li>

            <li>
              <a class="dropdown-item" href="#" id="logoutLink">Log Out</a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </ul>
        </li>
      </ul>

    </div>
    <!-- Collapsible wrapper -->
  </div>
  <!-- Container wrapper -->
</nav>
<!-- Navbar -->

<!-- Include SweetAlert2 and Axios -->

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Logout confirmation with SweetAlert2
    document.getElementById('logoutLink').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default action

        // SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to log back in!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log out!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the logout form
                document.getElementById('logout-form').submit();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    var navbar = document.getElementById('mainNavbar');
    var sticky = 200; // Scroll threshold

    function handleScroll() {
        if (window.pageYOffset >= sticky) {
            navbar.classList.add('sticky-top');
            document.body.classList.add('fixed-nav');
        } else {
            navbar.classList.remove('sticky-top');
            document.body.classList.remove('fixed-nav');
        }
    }

    window.onscroll = handleScroll;

   
});


</script>
