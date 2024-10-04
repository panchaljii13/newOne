<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Management System</title>
    <!-- CSS and JS links -->
</head>
<body>
    <nav>
        <ul>
            <li><a href="{{ route('home') }}">Home</a></li>

            @if (Auth::check())
                <li><a href="{{ route('profile') }}">Profile</a></li>
                
                @if (Auth::user()->role === 'super_admin')
                    <li><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                    <li><a href="{{ route('manage.users') }}">Manage Users</a></li>
                @endif

                <li><a href="{{ route('logout') }}">Logout</a></li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            @endif
        </ul>
    </nav>

    <div>
        @yield('content')
    </div>

</body>
</html>
