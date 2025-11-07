<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Password Reset Success - Laravel Multi-Level User</title>
    
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
            background: linear-gradient(135deg, #22c55e 0%, #0ea5e9 100%);
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
                transform: scale(0) rotate(-180deg);
                opacity: 0;
            }
            to {
                transform: scale(1) rotate(0deg);
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

        /* Success Message Box */
        .success-message-box {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 0.5rem;
        }

        .dark .success-message-box {
            background-color: rgba(20, 83, 45, 0.2);
            border-color: #166534;
        }

        .success-message-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #166534;
        }

        .dark .success-message-content {
            color: #86efac;
        }

        .success-message-icon {
            color: #22c55e;
        }

        /* Security Tips Box */
        .tips-box {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
            text-align: left;
        }

        .dark .tips-box {
            background-color: #1f2937;
        }

        .tips-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dark .tips-title {
            color: #d1d5db;
        }

        .tips-icon {
            color: #eab308;
        }

        .tips-list {
            font-size: 0.75rem;
            color: #6b7280;
            list-style: disc;
            padding-left: 1.25rem;
            line-height: 1.8;
        }

        .dark .tips-list {
            color: #9ca3af;
        }

        .tips-list li {
            margin-bottom: 0.25rem;
        }

        /* Button */
        .btn-primary {
            width: 100%;
            padding: 0.625rem 1rem;
            background-color: #22c55e;
            color: white;
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

        .btn-primary:hover {
            background-color: #16a34a;
        }

        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.3);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        /* Countdown */
        .countdown {
            margin-top: 1rem;
            font-size: 0.75rem;
            color: #6b7280;
        }

        .dark .countdown {
            color: #9ca3af;
        }

        .countdown-number {
            font-weight: 600;
            color: #22c55e;
        }

        .dark .countdown-number {
            color: #4ade80;
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
            <h1 class="success-title">Password Successfully Changed!</h1>
            
            <!-- Description -->
            <p class="success-description">
                Your password has been successfully changed. 
                Please login using your new password.
            </p>

            <!-- Success Message Box -->
            <div class="success-message-box">
                <div class="success-message-content">
                    <i class="fas fa-shield-alt success-message-icon"></i>
                    <span>Your account is now more secure with the new password.</span>
                </div>
            </div>

            <!-- Security Tips -->
            <div class="tips-box">
                <p class="tips-title">
                    <i class="fas fa-lightbulb tips-icon"></i>
                    <span>Security Tips:</span>
                </p>
                <ul class="tips-list">
                    <li>Never share your password with anyone</li>
                    <li>Use a strong and unique password</li>
                    <li>Change your password regularly</li>
                    <li>Don't use the same password for multiple accounts</li>
                </ul>
            </div>

            <!-- Action Button -->
            <a href="{{ route('login') }}" class="btn-primary">
                <i class="fas fa-sign-in-alt"></i>
                <span>Login Now</span>
            </a>

            <!-- Countdown Timer -->
            <div class="countdown">
                <p>
                    Redirecting to login page in <span id="countdown" class="countdown-number">5</span> seconds...
                </p>
            </div>
        </div>
    </div>

    <script>
        let countdown = 5;
        const countdownElement = document.getElementById('countdown');
        const loginRoute = "{{ route('login') }}";
        
        const timer = setInterval(() => {
            countdown--;
            if (countdownElement) {
                countdownElement.textContent = countdown;
            }
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = loginRoute;
            }
        }, 1000);
    </script>
</body>
</html>
