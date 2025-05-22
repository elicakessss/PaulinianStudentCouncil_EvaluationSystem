<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PSG Evaluation System') }}</title>

    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #0d532d;
            --primary-dark: #084023;
            --primary-light: #156e3d;
            --secondary-color: #5F8D4E;
            --accent-color: #A4BE7B;
            --light-color: #E5F9DB;
            --font-size-base: 14px;
            --font-size-sm: 13px;
            --font-size-xs: 12px;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            font-size: var(--font-size-base);
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        /* Typography adjustments for modern look */
        h1, .h1 { font-size: 1.75rem; font-weight: 600; } /* ~28px */
        h2, .h2 { font-size: 1.5rem; font-weight: 600; }  /* ~24px */
        h3, .h3 { font-size: 1.25rem; font-weight: 500; } /* ~20px */
        h4, .h4 { font-size: 1.125rem; font-weight: 500; } /* ~18px */
        h5, .h5 { font-size: 1rem; font-weight: 500; }    /* ~16px */
        h6, .h6 { font-size: 0.875rem; font-weight: 500; } /* ~14px */

        .card-header h1, .card-header h2, .card-header h3,
        .card-header h4, .card-header h5, .card-header h6 {
            margin-bottom: 0;
        }

        /* Header styles */
        .header {
            background: linear-gradient(to right, var(--primary-dark), var(--primary-color), var(--primary-light));
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 20px;
            position: fixed;
            top: 0;
            right: 0;
            left: 240px;
            z-index: 1000;
            color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-name {
            margin-right: 10px;
            font-weight: 400;
            font-size: var(--font-size-sm);
            letter-spacing: 0.2px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            font-size: var(--font-size-xs);
        }

        /* Sidebar styles */
        .sidebar {
            background: linear-gradient(to bottom, var(--primary-dark), var(--primary-color));
            color: #fff;
            height: 100vh;
            position: fixed;
            width: 240px;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .logo-img {
            width: 70px;
            height: 70px;
            margin-bottom: 10px;
        }

        .sidebar-logo h5 {
            font-size: var(--font-size-base);
            font-weight: 500;
            margin-bottom: 0;
            letter-spacing: 0.2px;
        }

        .main-content {
            margin-left: 240px;
            margin-top: 56px;
            padding: 20px 25px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 8px 16px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            font-size: var(--font-size-sm);
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 18px;
            text-align: center;
            font-size: var(--font-size-sm);
        }

        .sidebar .section-header {
            font-size: var(--font-size-xs);
            text-transform: uppercase;
            padding: 8px 16px 4px;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Alert styles */
        .alert {
            font-size: var(--font-size-sm);
            padding: 0.5rem 1rem;
        }

        /* Table Styles */
        .table {
            font-size: var(--font-size-sm);
        }
        .table th {
            font-weight: 500;
        }

        /* Form Styles */
        .form-label {
            font-size: var(--font-size-sm);
            font-weight: 500;
        }
        .form-control {
            font-size: var(--font-size-sm);
        }
        .form-text {
            font-size: var(--font-size-xs);
        }
        .btn {
            font-size: var(--font-size-sm);
        }

        /* Card Styles */
        .card-title {
            font-size: 1.125rem;
            font-weight: 500;
        }
        .card-text {
            font-size: var(--font-size-sm);
        }

        /* Dropdown style */
        .dropdown-menu {
            font-size: var(--font-size-sm);
        }

        /* Profile picture styles */
        .profile-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-circle-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .profile-circle-lg {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Avatar container specific styles */
        .navbar-avatar {
            overflow: hidden;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/psg-logo.png') }}" alt="PSG Logo" class="logo-img" onerror="this.src='https://via.placeholder.com/70'">
            <h5>PSG Evaluation System</h5>
        </div>

        <ul class="nav flex-column mt-2">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <div class="section-header">My Organization</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.organization*') ? 'active' : '' }}" href="{{ route('admin.organization') }}">
                    <i class="fas fa-sitemap"></i> Organizations
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.organization.evaluation') ? 'active' : '' }}" href="{{ route('admin.organization.evaluation') }}">
                    <i class="fas fa-clipboard-check"></i> Evaluations
                </a>
            </li>

            <li class="nav-item">
                <div class="section-header">System Management</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-users"></i> User Management
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.system.organizations*') ? 'active' : '' }}" href="{{ route('admin.system.organizations.index') }}">
                    <i class="fas fa-building"></i> Organization Management
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.system.reports') ? 'active' : '' }}" href="{{ route('admin.system.reports') }}">
                    <i class="fas fa-chart-bar"></i> Evaluation Reports
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.system.logs') ? 'active' : '' }}" href="{{ route('admin.system.logs') }}">
                    <i class="fas fa-history"></i> System Logs
                </a>
            </li>

            <li class="nav-item">
                <div class="section-header">Personalization</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.account*') ? 'active' : '' }}" href="{{ route('admin.account') }}">
                    <i class="fas fa-user-cog"></i> Account
                </a>
            </li>

            <li class="nav-item mt-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-left w-100">
                        <i class="fas fa-sign-out-alt"></i> Log Out
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="user-info">
            <div class="user-name">{{ Auth::guard('admin')->user()->first_name ?? 'Elijah' }} {{ Auth::guard('admin')->user()->last_name ?? 'Alonzo' }}</div>
            <div class="dropdown">
                <a href="#" class="dropdown-toggle d-flex align-items-center" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none; color: white;">
                    @if(Auth::guard('admin')->user()->profile_picture)
                        <div class="navbar-avatar">
                            <img src="{{ asset('storage/' . Auth::guard('admin')->user()->profile_picture) }}" alt="Profile Picture" style="width: 32px; height: 32px; object-fit: cover;">
                        </div>
                    @else
                        <div class="navbar-avatar">
                            {{ substr(Auth::guard('admin')->user()->first_name ?? 'E', 0, 1) }}
                        </div>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.account') }}">
                            <i class="fas fa-user-cog me-2"></i> My Account
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="main-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
