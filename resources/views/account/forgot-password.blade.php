<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Forgot Password - Laravel Multi-Level User</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Background */
        .forgot-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Container */
        .forgot-container {
            width: 100%;
            max-width: 28rem;
        }

        /* Logo Section */
        .forgot-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .forgot-logo-icon {
            width: 4rem;
            height: 4rem;
            margin: 0 auto 1rem;
            background: #ea580c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .forgot-logo-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .forgot-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .forgot-subtitle {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Card */
        .forgot-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }

        .dark .forgot-card {
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
            border-color: #ea580c;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
        }

        .dark .form-input {
            border-color: #4b5563;
            background-color: #374151;
            color: white;
        }

        .dark .form-input:focus {
            border-color: #fb923c;
            box-shadow: 0 0 0 3px rgba(251, 146, 60, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .input-hint {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .dark .input-hint {
            color: #9ca3af;
        }

        /* Alert Messages */
        .alert {
            margin-bottom: 1.5rem;
            border-radius: 0.375rem;
            padding: 1rem;
        }

        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .dark .alert-success {
            background-color: rgba(20, 83, 45, 0.2);
            border-color: #166534;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
        }

        .dark .alert-error {
            background-color: rgba(127, 29, 29, 0.2);
            border-color: #991b1b;
        }

        .alert-content {
            display: flex;
            gap: 0.75rem;
        }

        .alert-icon {
            flex-shrink: 0;
        }

        .alert-icon-success {
            color: #22c55e;
        }

        .alert-icon-error {
            color: #f87171;
        }

        .alert-text {
            flex: 1;
        }

        .alert-title {
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .alert-title-success {
            color: #166534;
        }

        .dark .alert-title-success {
            color: #86efac;
        }

        .alert-title-error {
            color: #991b1b;
        }

        .dark .alert-title-error {
            color: #fca5a5;
        }

        .alert-message {
            font-size: 0.875rem;
            color: #166534;
        }

        .dark .alert-message {
            color: #86efac;
        }

        .alert-list {
            font-size: 0.875rem;
            color: #b91c1c;
            list-style: disc;
            padding-left: 1.25rem;
        }

        .dark .alert-list {
            color: #f87171;
        }

        .alert-list li {
            margin-bottom: 0.25rem;
        }

        /* Submit Button */
        .submit-button {
            width: 100%;
            padding: 0.625rem 1rem;
            background-color: #ea580c;
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
            background-color: #c2410c;
        }

        .submit-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.3);
        }

        .submit-button:active {
            transform: scale(0.98);
        }

        .submit-icon {
            font-size: 1rem;
        }

        /* Back Link */
        .back-link {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .dark .back-link {
            color: #9ca3af;
        }

        .back-link a {
            font-weight: 500;
            color: #ea580c;
            text-decoration: none;
            transition: color 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-link a:hover {
            color: #c2410c;
        }

        .dark .back-link a {
            color: #fb923c;
        }

        .dark .back-link a:hover {
            color: #fdba74;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .forgot-card {
                padding: 1.5rem;
            }

            .forgot-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body class="forgot-bg">
    <div class="forgot-container">
        <!-- Logo and Title -->
        <div class="forgot-logo">
            <div class="forgot-logo-icon">
                <i class="fas fa-key"></i>
            </div>
            <h2 class="forgot-title">Forgot Password?</h2>
            <p class="forgot-subtitle">Enter your email to receive a reset link</p>
        </div>

        <!-- Forgot Password Form -->
        <div class="forgot-card">
            @if (session('status'))
                <div class="alert alert-success">
                    <div class="alert-content">
                        <i class="fas fa-check-circle alert-icon alert-icon-success"></i>
                        <div class="alert-text">
                            <p class="alert-message">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <div class="alert-content">
                        <i class="fas fa-exclamation-triangle alert-icon alert-icon-error"></i>
                        <div class="alert-text">
                            <h3 class="alert-title alert-title-error">Error</h3>
                            <ul class="alert-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
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
                            autofocus 
                            value="{{ old('email') }}"
                            class="form-input"
                            placeholder="Enter your email address"
                        >
                    </div>
                    <p class="input-hint">We'll send you a link to reset your password</p>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-button">
                    <i class="fas fa-paper-plane submit-icon"></i>
                    <span>Send Reset Link</span>
                </button>
            </form>

            <!-- Back to Login -->
            <div class="back-link">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to login</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
