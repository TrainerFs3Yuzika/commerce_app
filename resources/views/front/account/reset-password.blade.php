<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">KuyBelanja</a></li>
                    <li class="breadcrumb-item">Atur Ulang Kata Sandi</li>
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
                    <div class="login-form">
                        <form action="{{ route('front.processResetPassword') }}" method="post">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <h4 class="modal-title">Atur Ulang Kata Sandi</h4>
                            <div class="form-group">
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                    placeholder="Kata Sandi Baru" name="new_password" value="">
                                @error('new_password')
                                    <p class="invalid-feedback">{{ $message }} </p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control @error('confirm_password') is-invalid @enderror"
                                    placeholder="Konfirmasi Kata Sandi" name="confirm_password" value="">
                                @error('confirm_password')
                                    <p class="invalid-feedback">{{ $message }} </p>
                                @enderror
                            </div>

                            <input type="submit" class="btn btn-dark btn-block btn-lg" value="Submit">
                        </form>
                        <div class="text-center small"><a href="{{ route('account.login') }}">Klik di sini untuk Masuk</a></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
