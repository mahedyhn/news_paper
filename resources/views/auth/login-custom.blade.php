@extends('frontend.master')

@section('title')
    Login - News Paper
@endsection

@section('content')
<style>
    .auth-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
        margin-top: -50px;
    }

    .auth-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 450px;
        margin: 0 auto;
        padding: 40px;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .auth-header h1 {
        color: #333;
        font-size: 2em;
        margin-bottom: 10px;
    }

    .auth-header p {
        color: #666;
        font-size: 1em;
    }

    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #28a745;
    }

    .alert-danger {
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

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1em;
        transition: border-color 0.3s;
        font-family: inherit;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 10px rgba(102, 126, 234, 0.2);
    }

    input.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.85em;
        margin-top: 5px;
    }

    .login-btn {
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

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .login-btn:active {
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
        color: #333;
        text-decoration: none;
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
        border-color: #4285F4;
    }

    .social-btn.facebook {
        border-color: #1877F2;
        color: #1877F2;
    }

    .social-btn.facebook:hover {
        background: #f0f7ff;
        border-color: #1877F2;
    }

    .social-btn.github {
        border-color: #333;
        color: #333;
    }

    .social-btn.github:hover {
        background: #f5f5f5;
        border-color: #333;
    }

    .footer-links {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        font-size: 0.9em;
    }

    .footer-links a {
        color: #667eea;
        text-decoration: none;
        transition: color 0.3s;
    }

    .footer-links a:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    @media (max-width: 480px) {
        .auth-container {
            padding: 30px 20px;
        }

        .auth-header h1 {
            font-size: 1.5em;
        }
    }
</style>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h1>📰 News Paper</h1>
            <p>Login to Your Account</p>
        </div>

        <!-- Display Success Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <strong>Success!</strong> {{ session('success') }}
            </div>
        @endif

        <!-- Display Error Messages -->
        @if(session('error'))
            <div class="alert alert-danger">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Validation Error!</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Social Login Buttons -->
        <div class="social-buttons">
            <a href="{{ route('auth.google') }}" class="social-btn google">
                <i class="fab fa-google"></i>
                <span>Login with Google</span>
            </a>

            <a href="{{ route('auth.facebook') }}" class="social-btn facebook">
                <i class="fab fa-facebook"></i>
                <span>Login with Facebook</span>
            </a>

            <a href="{{ route('auth.github') }}" class="social-btn github">
                <i class="fab fa-github"></i>
                <span>Login with GitHub</span>
            </a>
        </div>

        <div class="divider">
            <span>OR</span>
        </div>

        <!-- Email/Password Login Form -->
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="your@email.com"
                    value="{{ old('email') }}"
                    required 
                    autofocus
                    @error('email') class="is-invalid" @enderror
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
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
                    @error('password') class="is-invalid" @enderror
                >
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div style="display: flex; align-items: center;">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember" style="display: inline; margin: 0; margin-left: 8px; font-weight: 400;">
                        Remember me
                    </label>
                </div>
            </div>

            <button type="submit" class="login-btn">
                Login
            </button>
        </form>

        <!-- Footer Links -->
        <div class="footer-links">
            <a href="{{ route('register') }}">Create Account</a>
            <a href="{{ route('password.request') }}">Forgot Password?</a>
        </div>
    </div>
</div>

<script>
    // Hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 500);
        }, 5000);
    });
</script>
@endsection
