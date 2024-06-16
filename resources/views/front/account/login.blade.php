<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

@extends('front.layouts.app')

@section('content')
<style>
    </style>
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

    <section class="section-10">
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
            <div class="row">
                <div class="col-md-6">
                <div id="carouselExampleIndicators1" class="carousel slide mb-4" data-ride="carousel" data-interval="2000" >
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators1" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators1" data-slide-to="1"></li> <!-- Ubah data-target ke carouselExampleIndicators1 -->
                    </ol>
                    <div class="carousel-inner" >
                        <div class="carousel-item active">
                            <img  class="d-block w-100" src="{{ asset('front-assets/images/Carousel 1-form.png') }}" alt="First slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="{{ asset('front-assets/images/Carousel 2-form.png') }}" alt="Second slide">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators1" role="button" data-slide="prev"> <!-- Ubah href ke carouselExampleIndicators1 -->
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators1" role="button" data-slide="next"> <!-- Ubah href ke carouselExampleIndicators1 -->
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
                </div>
                <div class="col-md-6">
                    <div class="login-form login-akun">
                        <form action="{{ route('account.authenticate') }}" method="post">
                            @csrf
                            <h4 class="modal-title">Masuk ke akun Anda</h4>
                            <div class="form-group">
                                <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email anda" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password" name="password" value="{{ old('password') }}">
                                @error('password')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group small">
                                <a href="{{ route('front.forgotPassword') }}" class="forgot-link">Lupa kata sandi?</a>
                            </div>
                            <input type="submit" class="btn btn-dark btn-block btn-lg" value="Masuk">
                        </form>
                        <div class="text-center small">Belum punya akun? <a href="{{ route('account.register') }}">Mendaftar</a></div>
                    </div>
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
