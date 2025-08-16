@extends('layouts.guest')

@section('title', 'Verifikasi Kode OTP')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-purple-50 to-pink-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-gradient-to-r from-green-600 to-blue-600 rounded-full flex items-center justify-center mb-4">
                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Verifikasi OTP</h2>
            <p class="mt-2 text-sm text-gray-600">
                Masukkan kode 6 digit yang dikirim ke<br>
                <span class="font-semibold text-blue-600">{{ $email }}</span>
            </p>
        </div>

        <!-- Form -->
        <div class="bg-white py-8 px-6 shadow-xl rounded-xl">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    @foreach($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.otp.verify') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode OTP
                    </label>
                    <div class="relative">
                        <input id="otp" name="otp" type="text" 
                               maxlength="6" pattern="[0-9]{6}" autocomplete="one-time-code" required
                               value="{{ old('otp') }}"
                               class="w-full px-3 py-4 text-center text-2xl font-bold tracking-widest border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('otp') border-red-500 @enderror"
                               placeholder="000000"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Masukkan 6 digit kode OTP</p>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Verifikasi Kode
                    </button>
                </div>

                <!-- Resend OTP -->
                <div class="text-center">
                    <a href="{{ route('password.otp.request') }}" 
                       class="text-sm text-gray-600 hover:text-gray-800 transition-colors duration-200">
                        ← Kembali ke Request OTP
                    </a>
                </div>
            </form>
        </div>

        <!-- Timer and Info -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                </svg>
                <div class="text-sm text-yellow-800">
                    <p class="font-semibold mb-1">Kode OTP berlaku selama 5 menit</p>
                    <p class="text-xs">Jika tidak menerima email, periksa folder spam atau kirim ulang OTP</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otp');
    
    // Auto-focus on OTP input
    otpInput.focus();
    
    // Auto-submit when 6 digits entered
    otpInput.addEventListener('input', function() {
        if (this.value.length === 6) {
            // Small delay to ensure user sees the complete code
            setTimeout(() => {
                this.form.submit();
            }, 500);
        }
    });
    
    // Handle paste event
    otpInput.addEventListener('paste', function(e) {
        e.preventDefault();
        let paste = (e.clipboardData || window.clipboardData).getData('text');
        // Extract only numbers and limit to 6 digits
        paste = paste.replace(/[^0-9]/g, '').slice(0, 6);
        this.value = paste;
        
        if (paste.length === 6) {
            setTimeout(() => {
                this.form.submit();
            }, 500);
        }
    });
});
</script>
@endsection
                    <p class="text-sm text-gray-600 mb-2">Tidak menerima kode?</p>
                    <form method="POST" action="{{ route('password.otp.resend') }}" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                        <button type="submit" 
                                class="text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200 underline"
                                onclick="this.disabled=true; this.innerText='Mengirim...'; this.form.submit();">
                            Kirim Ulang OTP
                        </button>
                    </form>
                </div>

                <div class="text-center">