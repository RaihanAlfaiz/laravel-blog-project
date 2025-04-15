@extends('frontend.master')

@section('title', 'Aktivasi 2FA - ' . config('app.name'))

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="mb-3">Aktivasi 2FA</h4>

                        <p>Scan QR code di bawah ini menggunakan aplikasi <strong>Google Authenticator</strong> atau
                            <strong>Authy</strong>:</p>

                        <div class="text-center my-3">
                            {!! $qrCode !!}
                        </div>

                        <div class="mb-3">
                            <p><strong>Manual Secret:</strong></p>
                            <div class="alert alert-light border text-monospace">{{ $secret }}</div>
                            <p class="text-muted small">Gunakan ini jika tidak bisa scan QR code.</p>
                        </div>

                        <hr>

                        <p class="text-muted mb-3">Setelah di-scan, masukkan 6 digit OTP dari aplikasi Anda:</p>

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

                            <button type="submit" class="btn btn-success w-100" id="submit-btn">Aktifkan 2FA</button>
                        </form>

                        <div class="text-muted small mt-3 text-center">
                            Jika Anda kehilangan akses ke aplikasi OTP, Anda harus menghubungi admin untuk reset 2FA.
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

        otpInput.addEventListener('input', function() {
            if (this.value.length === 6) {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Memverifikasi...';
                form.submit();
            }
        });

        otpInput.addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });
    </script>
@endpush
