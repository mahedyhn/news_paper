<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - News Paper</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .register-header h1 {
            color: #333;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .register-header p {
            color: #666;
            font-size: 1em;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .alert.show {
            display: block;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #dc3545;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.2);
        }

        .password-requirements {
            background: #f0f7ff;
            border-left: 3px solid #667eea;
            padding: 12px;
            border-radius: 5px;
            font-size: 0.9em;
            color: #555;
            margin-top: 8px;
            display: none;
        }

        .password-requirements.show {
            display: block;
        }

        .requirement {
            padding: 4px 0;
        }

        .requirement.met {
            color: #28a745;
        }

        .requirement.unmet {
            color: #dc3545;
        }

        .requirement i {
            margin-right: 5px;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            color: #999;
            position: relative;
        }

        .divider::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            height: 1px;
            width: 45%;
            background: #ddd;
        }

        .divider::after {
            content: "";
            position: absolute;
            right: 0;
            top: 50%;
            height: 1px;
            width: 45%;
            background: #ddd;
        }

        .divider span {
            background: white;
            padding: 0 10px;
            position: relative;
        }

        .social-buttons {
            display: grid;
            gap: 12px;
            margin-bottom: 30px;
        }

        .social-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            font-weight: 600;
            transition: all 0.3s;
            gap: 10px;
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .social-btn i {
            font-size: 1.2em;
        }

        .social-btn.google {
            border-color: #4285F4;
            color: #4285F4;
        }

        .social-btn.google:hover {
            background: #f0f7ff;
        }

        .social-btn.facebook {
            border-color: #1877F2;
            color: #1877F2;
        }

        .social-btn.facebook:hover {
            background: #f0f7ff;
        }

        .social-btn.github {
            border-color: #333;
            color: #333;
        }

        .social-btn.github:hover {
            background: #f5f5f5;
        }

        .footer-link {
            text-align: center;
            margin-top: 20px;
        }

        .footer-link a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 30px 20px;
            }

            .register-header h1 {
                font-size: 1.5em;
            }
        }

        .loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .loading::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }

        .terms-checkbox input {
            margin-top: 4px;
            cursor: pointer;
        }

        .terms-checkbox label {
            margin: 0;
            font-weight: 400;
            font-size: 0.9em;
        }

        .terms-checkbox a {
            color: #667eea;
            text-decoration: none;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>📰 News Paper</h1>
            <p>Create Your Account</p>
        </div>

        <!-- Display Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success show">
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error show">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        <!-- Social Register Buttons -->
        <div class="social-buttons">
            <a href="{{ route('auth.google') }}" class="social-btn google">
                <i class="fab fa-google"></i>
                <span>Sign up with Google</span>
            </a>

            <a href="{{ route('auth.facebook') }}" class="social-btn facebook">
                <i class="fab fa-facebook"></i>
                <span>Sign up with Facebook</span>
            </a>

            <a href="{{ route('auth.github') }}" class="social-btn github">
                <i class="fab fa-github"></i>
                <span>Sign up with GitHub</span>
            </a>
        </div>

        <div class="divider">
            <span>OR</span>
        </div>

        <!-- Email/Password Registration Form -->
        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="John Doe"
                    value="{{ old('name') }}"
                    required 
                    autofocus
                    @error('name') is-invalid @enderror
                >
                @error('name')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="your@email.com"
                    value="{{ old('email') }}"
                    required
                    @error('email') is-invalid @enderror
                >
                @error('email')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                    @error('password') is-invalid @enderror
                    onchange="checkPassword()"
                    oninput="checkPassword()"
                >
                @error('password')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
                
                <div class="password-requirements" id="passwordReqs">
                    <div class="requirement unmet" id="req-length">
                        <i class="fas fa-circle"></i> At least 8 characters
                    </div>
                    <div class="requirement unmet" id="req-uppercase">
                        <i class="fas fa-circle"></i> One uppercase letter
                    </div>
                    <div class="requirement unmet" id="req-number">
                        <i class="fas fa-circle"></i> One number
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="••••••••"
                    required
                    @error('password_confirmation') is-invalid @enderror
                >
                @error('password_confirmation')
                    <small style="color: #dc3545;">{{ $message }}</small>
                @enderror
            </div>

            <div class="terms-checkbox">
                <input 
                    type="checkbox" 
                    id="terms" 
                    name="terms" 
                    required
                    @error('terms') is-invalid @enderror
                >
                <label for="terms">
                    I agree to the 
                    <a href="#" target="_blank">Terms of Service</a> and 
                    <a href="#" target="_blank">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" class="register-btn" id="submitBtn">
                <span id="btnText">Create Account</span>
            </button>
        </form>

        <!-- Footer Link -->
        <div class="footer-link">
            Already have an account? 
            <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>

    <script>
        function checkPassword() {
            const password = document.getElementById('password').value;
            const reqsDiv = document.getElementById('passwordReqs');
            
            // Show requirements div when user starts typing
            if (password.length > 0) {
                reqsDiv.classList.add('show');
            } else {
                reqsDiv.classList.remove('show');
            }

            // Check length (8+ characters)
            const lengthReq = document.getElementById('req-length');
            if (password.length >= 8) {
                lengthReq.classList.remove('unmet');
                lengthReq.classList.add('met');
                lengthReq.innerHTML = '<i class="fas fa-check-circle"></i> At least 8 characters';
            } else {
                lengthReq.classList.add('unmet');
                lengthReq.classList.remove('met');
                lengthReq.innerHTML = '<i class="fas fa-circle"></i> At least 8 characters';
            }

            // Check uppercase
            const uppercaseReq = document.getElementById('req-uppercase');
            if (/[A-Z]/.test(password)) {
                uppercaseReq.classList.remove('unmet');
                uppercaseReq.classList.add('met');
                uppercaseReq.innerHTML = '<i class="fas fa-check-circle"></i> One uppercase letter';
            } else {
                uppercaseReq.classList.add('unmet');
                uppercaseReq.classList.remove('met');
                uppercaseReq.innerHTML = '<i class="fas fa-circle"></i> One uppercase letter';
            }

            // Check number
            const numberReq = document.getElementById('req-number');
            if (/[0-9]/.test(password)) {
                numberReq.classList.remove('unmet');
                numberReq.classList.add('met');
                numberReq.innerHTML = '<i class="fas fa-check-circle"></i> One number';
            } else {
                numberReq.classList.add('unmet');
                numberReq.classList.remove('met');
                numberReq.innerHTML = '<i class="fas fa-circle"></i> One number';
            }
        }

        // Add loading state to form submission
        document.getElementById('registerForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });

        // Hide alerts after 4 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.classList.contains('show')) {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                }, 4000);
            }
        });
    </script>
</body>
</html>
