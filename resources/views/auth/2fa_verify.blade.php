@extends('frontend.master')

@section('title', 'Verifikasi 2FA - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="mb-3">Verifikasi 2FA</h4>

                        <p>Hai <strong>{{ Auth::user()->name }}</strong>, akun kamu telah mengaktifkan 2FA.</p>
                        <p class="text-muted">Masukkan 6 digit kode dari Google Authenticator atau Authy:</p>

                        @if ($errors->has('otp'))
                            <div class="alert alert-danger">
                                {{ $errors->first('otp') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('2fa.verify') }}" id="otp-form">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="otp">Kode OTP</label>
                                <input type="text" name="otp" id="otp" maxlength="6" inputmode="numeric"
                                    pattern="\d*" class="form-control text-center fs-4 fw-bold" placeholder="••••••"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="submit-btn">
                                Verifikasi
                            </button>
                        </form>

                        <div class="text-muted small mt-3 text-center">
                            Tidak punya akses ke aplikasi OTP? <br>
                            <a href="#">Hubungi admin untuk reset 2FA.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const otpInput = document.getElementById('otp');
        const form = document.getElementById('otp-form');
        const submitBtn = document.getElementById('submit-btn');

        // Auto-submit if input reaches 6 digits
        otpInput.addEventListener('input', function() {
            if (this.value.length === 6) {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Memverifikasi...';
                form.submit();
            }
        });

        // Prevent non-digit input
        otpInput.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
    </script>
@endpush
