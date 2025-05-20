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
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
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
            font-size: 0.85rem;
            letter-spacing: 0.2px;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            font-size: 13px;
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
            font-size: 14px;
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
            font-size: 0.85rem;
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
            font-size: 0.9rem;
        }

        .sidebar .section-header {
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 8px 16px 4px;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Alert styles */
        .alert {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
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
                <a class="nav-link {{ Route::is('adviser.dashboard') ? 'active' : '' }}" href="{{ route('adviser.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <div class="section-header">My Organization</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('adviser.organization') ? 'active' : '' }}" href="{{ route('adviser.organization') }}">
                    <i class="fas fa-building"></i> Organization
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('adviser.students') ? 'active' : '' }}" href="{{ route('adviser.students') }}">
                    <i class="fas fa-user-graduate"></i> Students
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('adviser.evaluation') ? 'active' : '' }}" href="{{ route('adviser.evaluation') }}">
                    <i class="fas fa-clipboard-check"></i> Evaluation
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('adviser.reports') ? 'active' : '' }}" href="{{ route('adviser.reports') }}">
                    <i class="fas fa-chart-bar"></i> Evaluation Report
                </a>
            </li>

            <li class="nav-item">
                <div class="section-header">Personalization</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Route::is('adviser.account') ? 'active' : '' }}" href="{{ route('adviser.account') }}">
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
            <div class="user-name">{{ Auth::guard('adviser')->user()->first_name ?? 'Adviser' }} {{ Auth::guard('adviser')->user()->last_name ?? 'User' }}</div>
            <div class="user-avatar">
                {{ substr(Auth::guard('adviser')->user()->first_name ?? 'A', 0, 1) }}
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
