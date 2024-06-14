@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">KuyBelanja</a></li>
                    <li class="breadcrumb-item">Masuk</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-10">
        <div class="container">

            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif
            <div class="login-form">
                <form action="{{ route('account.authenticate') }}" method="post">
                    @csrf
                    <h4 class="modal-title">Masuk ke akun Anda</h4>
                    <div class="form-group">
                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                            placeholder="Masukkan email anda" name="email" value="{{ old('email') }}">
                        @error('email')
                            <p class="invalid-feedback">{{ $message }} </p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan password" name="password" value="{{ old('password') }}">
                        @error('password')
                            <p class="invalid-feedback">{{ $message }} </p>
                        @enderror
                    </div>
                    <div class="form-group small">
                        <a href="{{ route('front.forgotPassword') }}" class="forgot-link">Tidak ingat kata sandi?</a>
                    </div>
                    <input type="submit" class="btn btn-dark btn-block btn-lg" value="Login">
                </form>
                <div class="text-center small">Belum punya akun? <a href="{{ route('account.register') }}">Mendaftar</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        function addToCart(productId) {
            // Assume isLoggedIn is a global variable that indicates whether the user is logged in
            var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

            if (!isLoggedIn) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap login terlebih dahulu!',
                    confirmButtonText: 'OK'
                });
                return; // Exit the function if the user is not logged in
            }

            $.ajax({
                url: '/add-to-cart', // Update the URL according to your route
                method: 'POST',
                data: {
                    id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            html: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Redirect to the cart page after the alert is closed
                            window.location.href = "{{ route('front.cart') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            title: 'Info',
                            text: response.message,
                            showConfirmButton: true,
                            confirmButtonText: 'Ok'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong!',
                        showConfirmButton: true,
                        confirmButtonText: 'Ok'
                    });
                }
            });
        }
    </script>
@endsection
