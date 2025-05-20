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
            --secondary-color: #3e8e58;
            --accent-color: #dce9d5;
            --background-color: #f5f5f5;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 900px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .login-row {
            display: flex;
            min-height: 500px;
        }

        .login-form {
            flex: 1;
            padding: 40px;
            position: relative;
            z-index: 1;
        }

        .login-brand {
            flex: 1;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .brand-circles {
            position: absolute;
            width: 200%;
            height: 200%;
        }

        .brand-circle {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .circle-1 {
            width: 600px;
            height: 600px;
            top: -200px;
            right: -200px;
        }

        .circle-2 {
            width: 500px;
            height: 500px;
            top: -150px;
            right: -150px;
        }

        .circle-3 {
            width: 400px;
            height: 400px;
            top: -100px;
            right: -100px;
        }

        .logo-container {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .logo-img {
            width: 180px;
            height: 180px;
        }

        .form-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            height: 50px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(62, 142, 88, 0.25);
        }

        .btn-submit {
            height: 50px;
            background-color: var(--primary-color);
            border: none;
            border-radius: 5px;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            background-color: #094525;
        }

        .forgot-password {
            display: block;
            text-align: center;
            color: var(--secondary-color);
            margin: 15px 0;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password:hover {
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .login-brand {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-row">
            <div class="login-form">
                <h2 class="form-title">Login</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="id_number" class="form-label">ID Number</label>
                        <input id="id_number" type="text" class="form-control @error('id_number') is-invalid @enderror" name="id_number" value="{{ old('id_number') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="" selected disabled>-- Select --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="adviser" {{ old('role') == 'adviser' ? 'selected' : '' }}>Adviser</option>
                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>

                    <a href="#" class="forgot-password">Forgot Password</a>

                    <button type="submit" class="btn btn-submit">
                        Submit
                    </button>
                </form>
            </div>

            <div class="login-brand">
                <div class="brand-circles">
                    <div class="brand-circle circle-1"></div>
                    <div class="brand-circle circle-2"></div>
                    <div class="brand-circle circle-3"></div>
                </div>

                <div class="logo-container">
                    <img src="{{ asset('images/psg-logo.png') }}" class="logo-img" alt="PSG Logo" onerror="this.src='https://via.placeholder.com/180?text=PSG+Logo'">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
