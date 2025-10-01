<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gaji DPR - @yield('title', 'Sistem Informasi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(90deg, #dc3545 0%, #a03449 100%) !important; /* Merah DPR */
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff !important;
        }

        .navbar-nav .nav-link {
            border-radius: 6px;
            margin: 0 5px;
            color: rgba(255,255,255,0.9) !important;
            transition: all 0.2s ease;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: #fff !important;
        }

        .navbar-nav .nav-link.active {
            background: #ffffff;
            color: #dc3545 !important;
            font-weight: 600;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }

        main.container {
            min-height: 80vh;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            padding: 30px;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        
        /* Gaya alert custom */
        .alert {
            border-radius: 6px;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ Auth::check() ? (Auth::user()->role === 'Admin' ? route('admin.dashboard') : route('public.dashboard')) : route('login') }}">
                <i class="fas fa-landmark me-2"></i> DPR Gaji
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(auth()->user()->role === 'Admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            @elseif(auth()->user()->role === 'Public')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('public.dashboard') ? 'active' : '' }}" href="{{ route('public.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->full_name }} ({{ Auth::user()->role }})
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>