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
          <a class="nav-link active" aria-current="page" href="Home">Home</a>
        </li>

        <li class="nav-item"></li>
        <a class="nav-link" href="{{ route('indexFolder') }}">Documents</a>
        </li>
        <!-- <li class="nav-item">
                        <a class="nav-link" href="...">My Doc</a>
                    </li> -->
        <li class="nav-item">
          <a class="nav-link" href="{{ route('downloadHistory') }}">Downloaded</a>
        </li>

      </ul>

      <!-- Left links -->

      <!-- Search form -->

      <ul class="navbar-nav mb-2 mb-lg-0">

        <!-- Navbar dropdown -->

        <!-- Dropdown menu -->


        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-mdb-toggle="dropdown"
            aria-expanded="false">
            <img src="https://c8.alamy.com/comp/TC2FPE/young-man-avatar-cartoon-character-profile-picture-TC2FPE.jpg"
              class="rounded-circle img-fluid" height='25' width='25'>
          </a>
          <!-- Dropdown menu -->
          <ul class="dropdown-menu dropdown-menu-end p-1" aria-labelledby="navbarDropdown">
            <li class="my-2 d-flex align-items-center"><img
                src="https://c8.alamy.com/comp/TC2FPE/young-man-avatar-cartoon-character-profile-picture-TC2FPE.jpg"
                class="rounded-circle img-fluid me-1" height='25' width='25'><span> {{auth()->user()->name}}</span></li>

            <li>
              <hr class="dropdown-divider" />
            </li>
            <li><a class="dropdown-item" href="ShpwProfile">Your Account</a></li>

            <li><a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a></li>
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