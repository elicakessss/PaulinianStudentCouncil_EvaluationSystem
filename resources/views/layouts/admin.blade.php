<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PSG Evaluation System') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #0d532d;
            --secondary-color: #5F8D4E;
            --accent-color: #A4BE7B;
            --light-color: #E5F9DB;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
        }

        .sidebar {
            background-color: var(--primary-color);
            color: #fff;
            min-height: 100vh;
            position: fixed;
            width: 250px;
        }

        .profile-section {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .profile-section h5 {
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .profile-section p {
            font-size: 0.8rem;
            margin-bottom: 0;
            opacity: 0.8;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px 30px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            font-size: 0.95rem;
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
            width: 20px;
            text-align: center;
        }

        .sidebar .section-header {
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 10px 20px 5px;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-section">
            <img src="{{ asset('images/default-profile.png') }}" alt="Profile" class="profile-img" onerror="this.src='https://via.placeholder.com/80'">
            <h5 class="mb-1">{{ Auth::guard('admin')->user()->first_name ?? 'Elijah' }} {{ Auth::guard('admin')->user()->last_name ?? 'Alonzo' }}</h5>
            <p class="text-light mb-0">Administrator</p>
        </div>

        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <div class="section-header">My Organization</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.organization') ? 'active' : '' }}" href="{{ route('admin.organization') }}">
                    <i class="fas fa-building"></i> Organization
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.organization.evaluation') ? 'active' : '' }}" href="{{ route('admin.organization.evaluation') }}">
                    <i class="fas fa-clipboard-check"></i> Evaluation
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
                    <i class="fas fa-sitemap"></i> Organization Management
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('admin.system.reports') ? 'active' : '' }}" href="{{ route('admin.system.reports') }}">
                    <i class="fas fa-chart-bar"></i> Evaluation Report
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
                <a class="nav-link {{ Route::is('admin.account') ? 'active' : '' }}" href="{{ route('admin.account') }}">
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
</body>
</html>
