<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Login - Laravel Multi-Level User</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Background */
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Card Container */
        .login-container {
            width: 100%;
            max-width: 28rem;
        }

        /* Logo Section */
        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo-icon {
            width: 4rem;
            height: 4rem;
            margin: 0 auto 1rem;
            background: #2563eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .login-logo-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .login-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Card */
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2rem;
        }

        .dark .login-card {
            background: rgba(31, 41, 55, 0.95);
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .dark .form-label {
            color: #d1d5db;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem 0.5rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            color: #111827;
            background-color: white;
            transition: all 0.15s ease-in-out;
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .dark .form-input {
            border-color: #4b5563;
            background-color: #374151;
            color: white;
        }

        .dark .form-input:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            transition: color 0.15s ease-in-out;
        }

        .password-toggle:hover {
            color: #4b5563;
        }

        /* Error Messages */
        .error-alert {
            margin-bottom: 1.5rem;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.375rem;
            padding: 1rem;
        }

        .dark .error-alert {
            background-color: rgba(127, 29, 29, 0.2);
            border-color: #991b1b;
        }

        .error-content {
            display: flex;
            gap: 0.75rem;
        }

        .error-icon {
            color: #f87171;
            flex-shrink: 0;
        }

        .error-text {
            flex: 1;
        }

        .error-title {
            font-size: 0.875rem;
            font-weight: 500;
            color: #991b1b;
            margin-bottom: 0.5rem;
        }

        .dark .error-title {
            color: #fca5a5;
        }

        .error-list {
            font-size: 0.875rem;
            color: #b91c1c;
            list-style: disc;
            padding-left: 1.25rem;
        }

        .dark .error-list {
            color: #f87171;
        }

        .error-list li {
            margin-bottom: 0.25rem;
        }

        /* Checkbox and Links */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
        }

        .form-checkbox {
            width: 1rem;
            height: 1rem;
            color: #2563eb;
            border-color: #d1d5db;
            border-radius: 0.25rem;
            cursor: pointer;
        }

        .checkbox-label {
            margin-left: 0.5rem;
            font-size: 0.875rem;
            color: #111827;
            cursor: pointer;
        }

        .dark .checkbox-label {
            color: #d1d5db;
        }

        .forgot-link {
            font-size: 0.875rem;
            font-weight: 500;
            color: #2563eb;
            text-decoration: none;
            transition: color 0.15s ease-in-out;
        }

        .forgot-link:hover {
            color: #1d4ed8;
        }

        .dark .forgot-link {
            color: #60a5fa;
        }

        .dark .forgot-link:hover {
            color: #93c5fd;
        }

        /* Submit Button */
        .submit-button {
            width: 100%;
            padding: 0.625rem 1rem;
            background-color: #2563eb;
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .submit-button:hover {
            background-color: #1d4ed8;
        }

        .submit-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
        }

        .submit-button:active {
            transform: scale(0.98);
        }

        .submit-icon {
            font-size: 1rem;
        }

        /* Register Link */
        .register-link {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .dark .register-link {
            color: #9ca3af;
        }

        .register-link a {
            font-weight: 500;
            color: #2563eb;
            text-decoration: none;
            transition: color 0.15s ease-in-out;
        }

        .register-link a:hover {
            color: #1d4ed8;
        }

        .dark .register-link a {
            color: #60a5fa;
        }

        .dark .register-link a:hover {
            color: #93c5fd;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .login-card {
                padding: 1.5rem;
            }

            .login-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="login-bg">
    <div class="login-container">
        <!-- Logo and Title -->
        <div class="login-logo">
            <div class="login-logo-icon">
                <i class="fas fa-network-wired"></i>
            </div>
            <h2 class="login-title">Welcome Back</h2>
            <p class="login-subtitle">Sign in to your account</p>
        </div>

        <!-- Login Form -->
        <div class="login-card">
            @if ($errors->any())
                <div class="error-alert">
                    <div class="error-content">
                        <i class="fas fa-exclamation-triangle error-icon"></i>
                        <div class="error-text">
                            <h3 class="error-title">Login failed</h3>
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                
                <!-- Email or Username -->
                <div class="form-group">
                    <label for="login_credential" class="form-label">
                        Email or Username
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            id="login_credential" 
                            name="login_credential" 
                            type="text" 
                            required 
                            autofocus
                            class="form-input"
                            placeholder="Enter your email or username"
                            value="{{ old('login_credential') }}"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        Password
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="form-input"
                            placeholder="Enter your password"
                            style="padding-right: 2.5rem;"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()" 
                            class="password-toggle"
                            aria-label="Toggle password visibility"
                        >
                            <i id="password-toggle" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <div class="checkbox-group">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="form-checkbox"
                        >
                        <label for="remember" class="checkbox-label">
                            Remember me
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-button">
                    <i class="fas fa-sign-in-alt submit-icon"></i>
                    <span>Sign in</span>
                </button>
            </form>

            <!-- Register Link -->
            <div class="register-link">
                <p>
                    Don't have an account? 
                    <a href="{{ route('register') }}">Sign up here</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.classList.remove('fa-eye');
                passwordToggle.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.remove('fa-eye-slash');
                passwordToggle.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
