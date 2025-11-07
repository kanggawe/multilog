<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Register - Laravel Multi-Level User</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Background */
        .register-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Container */
        .register-container {
            width: 100%;
            max-width: 28rem;
        }

        /* Logo Section */
        .register-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-logo-icon {
            width: 4rem;
            height: 4rem;
            margin: 0 auto 1rem;
            background: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .register-logo-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .register-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .register-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Card */
        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .dark .register-card {
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
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .dark .form-input {
            border-color: #4b5563;
            background-color: #374151;
            color: white;
        }

        .dark .form-input:focus {
            border-color: #4ade80;
            box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.1);
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

        .input-hint {
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .dark .input-hint {
            color: #9ca3af;
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

        /* Checkbox */
        .checkbox-group {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .form-checkbox {
            width: 1rem;
            height: 1rem;
            color: #16a34a;
            border-color: #d1d5db;
            border-radius: 0.25rem;
            cursor: pointer;
            margin-top: 0.125rem;
        }

        .checkbox-label {
            margin-left: 0.5rem;
            font-size: 0.875rem;
            color: #111827;
            cursor: pointer;
            line-height: 1.5;
        }

        .dark .checkbox-label {
            color: #d1d5db;
        }

        .checkbox-label a {
            color: #16a34a;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s ease-in-out;
        }

        .checkbox-label a:hover {
            color: #15803d;
        }

        .dark .checkbox-label a {
            color: #4ade80;
        }

        .dark .checkbox-label a:hover {
            color: #86efac;
        }

        /* Submit Button */
        .submit-button {
            width: 100%;
            padding: 0.625rem 1rem;
            background-color: #16a34a;
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
            background-color: #15803d;
        }

        .submit-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.3);
        }

        .submit-button:active {
            transform: scale(0.98);
        }

        .submit-icon {
            font-size: 1rem;
        }

        /* Register Link */
        .login-link {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .dark .login-link {
            color: #9ca3af;
        }

        .login-link a {
            font-weight: 500;
            color: #16a34a;
            text-decoration: none;
            transition: color 0.15s ease-in-out;
        }

        .login-link a:hover {
            color: #15803d;
        }

        .dark .login-link a {
            color: #4ade80;
        }

        .dark .login-link a:hover {
            color: #86efac;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .register-card {
                padding: 1.5rem;
            }

            .register-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="register-bg">
    <div class="register-container">
        <!-- Logo and Title -->
        <div class="register-logo">
            <div class="register-logo-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2 class="register-title">Create Account</h2>
            <p class="register-subtitle">Join our system</p>
        </div>

        <!-- Register Form -->
        <div class="register-card">
            @if ($errors->any())
                <div class="error-alert">
                    <div class="error-content">
                        <i class="fas fa-exclamation-triangle error-icon"></i>
                        <div class="error-text">
                            <h3 class="error-title">Registration failed</h3>
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf
                
                <!-- Full Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            required 
                            class="form-input"
                            placeholder="Enter your full name"
                            value="{{ old('name') }}"
                        >
                    </div>
                </div>
                
                <!-- Username -->
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-at input-icon"></i>
                        <input 
                            id="username" 
                            name="username" 
                            type="text" 
                            required 
                            class="form-input"
                            placeholder="Enter your username"
                            value="{{ old('username') }}"
                        >
                    </div>
                </div>
                
                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            required 
                            class="form-input"
                            placeholder="Enter your email"
                            value="{{ old('email') }}"
                        >
                    </div>
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <div class="input-wrapper">
                        <i class="fas fa-phone input-icon"></i>
                        <input 
                            id="phone_number" 
                            name="phone_number" 
                            type="tel" 
                            class="form-input"
                            placeholder="Enter your phone number"
                            value="{{ old('phone_number') }}"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="form-input"
                            placeholder="Create a password"
                            style="padding-right: 2.5rem;"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password')" 
                            class="password-toggle"
                            aria-label="Toggle password visibility"
                        >
                            <i id="password-toggle" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="input-hint">Password must be at least 8 characters long</p>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            required 
                            class="form-input"
                            placeholder="Confirm your password"
                            style="padding-right: 2.5rem;"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password_confirmation')" 
                            class="password-toggle"
                            aria-label="Toggle password visibility"
                        >
                            <i id="password-confirmation-toggle" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="checkbox-group">
                    <input 
                        id="terms" 
                        name="terms" 
                        type="checkbox" 
                        required
                        class="form-checkbox"
                    >
                    <label for="terms" class="checkbox-label">
                        I agree to the 
                        <a href="#">Terms of Service</a>
                        and 
                        <a href="#">Privacy Policy</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-button">
                    <i class="fas fa-user-plus submit-icon"></i>
                    <span>Create Account</span>
                </button>
            </form>

            <!-- Login Link -->
            <div class="login-link">
                <p>
                    Already have an account? 
                    <a href="{{ route('login') }}">Sign in here</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleId = fieldId === 'password' ? 'password-toggle' : 'password-confirmation-toggle';
            const passwordToggle = document.getElementById(toggleId);
            
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
