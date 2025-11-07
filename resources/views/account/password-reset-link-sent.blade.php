<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Reset Link Sent - Laravel Multi-Level User</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Background */
        .success-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        /* Container */
        .success-container {
            width: 100%;
            max-width: 28rem;
        }

        /* Card */
        .success-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            text-align: center;
        }

        .dark .success-card {
            background: rgba(31, 41, 55, 0.95);
        }

        /* Success Icon */
        .success-icon-wrapper {
            width: 5rem;
            height: 5rem;
            margin: 0 auto 1rem;
            background: #22c55e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-icon-wrapper i {
            font-size: 2rem;
            color: white;
            animation: checkmark 0.6s ease-out 0.3s both;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Typography */
        .success-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .dark .success-title {
            color: white;
        }

        .success-description {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .dark .success-description {
            color: #9ca3af;
        }

        /* Info Box */
        .info-box {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #dbeafe;
            border: 1px solid #93c5fd;
            border-radius: 0.5rem;
            text-align: left;
        }

        .dark .info-box {
            background-color: rgba(30, 58, 138, 0.2);
            border-color: #1e40af;
        }

        .info-box-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: #1e40af;
        }

        .dark .info-box-content {
            color: #93c5fd;
        }

        .info-box-icon {
            color: #2563eb;
            flex-shrink: 0;
        }

        .dark .info-box-icon {
            color: #60a5fa;
        }

        .info-box strong {
            font-weight: 600;
        }

        /* Instructions Box */
        .instructions-box {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
            text-align: left;
        }

        .dark .instructions-box {
            background-color: #1f2937;
        }

        .instructions-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dark .instructions-title {
            color: #d1d5db;
        }

        .instructions-icon {
            color: #2563eb;
        }

        .dark .instructions-icon {
            color: #60a5fa;
        }

        .instructions-list {
            font-size: 0.75rem;
            color: #6b7280;
            list-style: disc;
            padding-left: 1.25rem;
            line-height: 1.8;
        }

        .dark .instructions-list {
            color: #9ca3af;
        }

        .instructions-list li {
            margin-bottom: 0.25rem;
        }

        .instructions-list strong {
            font-weight: 600;
            color: #374151;
        }

        .dark .instructions-list strong {
            color: #d1d5db;
        }

        /* Buttons */
        .button-group {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .btn {
            width: 100%;
            padding: 0.625rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .dark .btn-secondary {
            background-color: #374151;
            color: #d1d5db;
            border-color: #4b5563;
        }

        .btn-secondary:hover {
            background-color: #e5e7eb;
        }

        .dark .btn-secondary:hover {
            background-color: #4b5563;
        }

        .btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
        }

        .btn:active {
            transform: scale(0.98);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .success-card {
                padding: 1.5rem;
            }

            .success-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body class="success-bg">
    <div class="success-container">
        <!-- Success Card -->
        <div class="success-card">
            <!-- Success Icon -->
            <div class="success-icon-wrapper">
                <i class="fas fa-check"></i>
            </div>
            
            <!-- Title -->
            <h1 class="success-title">Reset Link Sent!</h1>
            
            <!-- Description -->
            <p class="success-description">
                We've sent a password reset link to your email address. 
                Please check your inbox and follow the instructions to reset your password.
            </p>

            <!-- Email Info -->
            @if(session('email'))
            <div class="info-box">
                <div class="info-box-content">
                    <i class="fas fa-envelope info-box-icon"></i>
                    <span>Email sent to: <strong>{{ session('email') }}</strong></span>
                </div>
            </div>
            @endif

            <!-- Instructions -->
            <div class="instructions-box">
                <p class="instructions-title">
                    <i class="fas fa-info-circle instructions-icon"></i>
                    <span>Instructions:</span>
                </p>
                <ul class="instructions-list">
                    <li>Check your <strong>Inbox</strong> or <strong>Spam</strong> folder</li>
                    <li>The reset link is valid for <strong>60 minutes</strong></li>
                    <li>If you don't receive the email, try again after a few minutes</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="button-group">
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Login</span>
                </a>
                
                <a href="{{ route('password.request') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                    <span>Resend Link</span>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
