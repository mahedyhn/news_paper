@extends('frontend.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0" style="border-radius: 10px;">
                    <div class="card-body p-5">
                        <h3 class="card-title mb-4 text-center" style="color: #6c5ce7; font-weight: bold;">
                            <i class="fas fa-key"></i> Forgot Password?
                        </h3>

                        <p class="text-muted text-center mb-4">
                            Enter your email address and we'll send you a link to reset your password.
                        </p>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="mb-0"><i class="fas fa-exclamation-circle"></i> Error</h6>
                                @foreach ($errors->all() as $error)
                                    <small class="d-block">{{ $error }}</small>
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}" class="needs-validation">
                            @csrf

                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="form-label" style="color: #2d3436; font-weight: 500;">
                                    <i class="fas fa-envelope"></i> Email Address
                                </label>
                                <input type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" placeholder="Enter your email address" required
                                    style="border-radius: 8px; border: 2px solid #dfe6e9; padding: 12px 15px;">
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-lg w-100 text-white"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 8px; font-weight: 600; padding: 12px;">
                                <i class="fas fa-paper-plane"></i> Send Reset Link
                            </button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Remembered your password?
                                <a href="{{ route('login') }}"
                                    style="color: #667eea; text-decoration: none; font-weight: 600;">
                                    Back to Login
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="alert alert-info mt-4" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Account Recovery Tips:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Check your spam/junk folder if you don't see the email</li>
                        <li>The reset link will expire in 60 minutes</li>
                        <li>Make sure to use a strong password</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-control-lg:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        button {
            transition: all 0.3s ease;
        }
    </style>
@endsection