@extends('frontend.master')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0" style="border-radius: 10px;">
                    <div class="card-body p-5">
                        <h3 class="card-title mb-4 text-center" style="color: #6c5ce7; font-weight: bold;">
                            <i class="fas fa-lock"></i> Reset Your Password
                        </h3>

                        <p class="text-muted text-center mb-4">
                            Enter your email and create a new password for your account.
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

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.update') }}" class="needs-validation">
                            @csrf

                            <!-- Hidden Token Field -->
                            <input type="hidden" name="token" value="{{ $token }}">

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

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label" style="color: #2d3436; font-weight: 500;">
                                    <i class="fas fa-lock"></i> New Password
                                </label>
                                <input type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Enter a strong password" required
                                    style="border-radius: 8px; border: 2px solid #dfe6e9; padding: 12px 15px;">
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <!-- Password Requirements -->
                                <div class="mt-3">
                                    <small style="color: #636e72; display: block;">
                                        <strong>Password Requirements:</strong>
                                    </small>
                                    <ul class="list-unstyled mt-2" id="passwordRequirements"
                                        style="font-size: 0.875rem; color: #636e72;">
                                        <li><span id="req-length" style="color: #d63031;">✗</span> At least 8 characters
                                        </li>
                                        <li><span id="req-uppercase" style="color: #d63031;">✗</span> One uppercase letter
                                            (A-Z)</li>
                                        <li><span id="req-number" style="color: #d63031;">✗</span> One number (0-9)</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label"
                                    style="color: #2d3436; font-weight: 500;">
                                    <i class="fas fa-check-circle"></i> Confirm Password
                                </label>
                                <input type="password"
                                    class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" name="password_confirmation"
                                    placeholder="Re-enter your password" required
                                    style="border-radius: 8px; border: 2px solid #dfe6e9; padding: 12px 15px;">
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <!-- Password Match Indicator -->
                                <div class="mt-2">
                                    <small id="passwordMatch" style="color: #636e72; display: none;">
                                        <span id="matchIcon" style="color: #d63031;">✗</span> Passwords match
                                    </small>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-lg w-100 text-white" id="submitBtn"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 8px; font-weight: 600; padding: 12px;"
                                disabled>
                                <i class="fas fa-save"></i> Reset Password
                            </button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Remember your password?
                                <a href="{{ route('login') }}"
                                    style="color: #667eea; text-decoration: none; font-weight: 600;">
                                    Back to Login
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');

        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqNumber = document.getElementById('req-number');
        const passwordMatch = document.getElementById('passwordMatch');
        const matchIcon = document.getElementById('matchIcon');

        function validatePassword() {
            const password = passwordInput.value;
            let isValid = true;

            // Check length
            if (password.length >= 8) {
                reqLength.style.color = '#27ae60';
                reqLength.textContent = '✓';
            } else {
                reqLength.style.color = '#d63031';
                reqLength.textContent = '✗';
                isValid = false;
            }

            // Check uppercase
            if (/[A-Z]/.test(password)) {
                reqUppercase.style.color = '#27ae60';
                reqUppercase.textContent = '✓';
            } else {
                reqUppercase.style.color = '#d63031';
                reqUppercase.textContent = '✗';
                isValid = false;
            }

            // Check number
            if (/[0-9]/.test(password)) {
                reqNumber.style.color = '#27ae60';
                reqNumber.textContent = '✓';
            } else {
                reqNumber.style.color = '#d63031';
                reqNumber.textContent = '✗';
                isValid = false;
            }

            checkPasswordMatch();
            updateSubmitButton();
            return isValid;
        }

        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;

            if (confirm.length > 0) {
                passwordMatch.style.display = 'block';
                if (password === confirm) {
                    matchIcon.style.color = '#27ae60';
                    matchIcon.textContent = '✓';
                    matchIcon.nextSibling.textContent = ' Passwords match';
                } else {
                    matchIcon.style.color = '#d63031';
                    matchIcon.textContent = '✗';
                    matchIcon.nextSibling.textContent = ' Passwords do not match';
                }
            } else {
                passwordMatch.style.display = 'none';
            }
        }

        function updateSubmitButton() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            const isPasswordValid = password.length >= 8 && /[A-Z]/.test(password) && /[0-9]/.test(password);
            const isMatching = password === confirm && confirm.length > 0;

            submitBtn.disabled = !(isPasswordValid && isMatching);

            if (submitBtn.disabled) {
                submitBtn.style.opacity = '0.6';
                submitBtn.style.cursor = 'not-allowed';
            } else {
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
            }
        }

        passwordInput.addEventListener('input', validatePassword);
        confirmInput.addEventListener('input', checkPasswordMatch);
        confirmInput.addEventListener('input', updateSubmitButton);
    </script>

    <style>
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-control-lg:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        button:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        button {
            transition: all 0.3s ease;
        }

        .list-unstyled li {
            margin-bottom: 8px;
        }
    </style>
@endsection