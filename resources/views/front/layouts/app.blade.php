<!DOCTYPE html>
<html class="no-js" lang="en_AU" />

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

    <meta name="HandheldFriendly" content="True" />
    <meta name="pinterest" content="nopin" />

    <meta property="og:locale" content="en_AU" />
    <meta property="og:type" content="website" />
    <meta property="fb:admins" content="" />
    <meta property="fb:app_id" content="" />
    <meta property="og:site_name" content="" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="" />
    <meta property="og:image:height" content="" />
    <meta property="og:image:alt" content="" />

    <meta name="twitter:title" content="" />
    <meta name="twitter:site" content="" />
    <meta name="twitter:description" content="" />
    <meta name="twitter:image" content="" />
    <meta name="twitter:image:alt" content="" />
    <meta name="twitter:card" content="summary_large_image" />


    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick-theme.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap"
        rel="stylesheet">

    {{-- SweetAlert --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Fav Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="#" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    .section-3 .cat-card .right:hover {
        background-color: #3ABEF9;
        color: #FFF
    }
    .sub-title:after{
        background-color: #3ABEF9;
    }
    .section-10 .login-form a {
        color: #000;
    }
    .section-title:after{   
        background-color: #3ABEF9;
    }
     .section-10 .login-form a:hover {
        color: #3ABEF9;
    }
    .btn-dark {
        padding: 10px 20px;
        background-color: #3572EF;
        border-radius: 0px;
    }
    .btn-dark:hover {
        background-color: #3ABEF9;
        border-color: #3ABEF9;
    }
    .icon-cart, .icon-home{
        color:#fff;
    }
    .text-primary {
        color: #3572EF !important;
    }
    .breadcrumb a{
        color:#3ABEF9;
    }
    .fa-heart{
        color:#FF0000;
    }
    .fa-heart:hover{
        color:#A7E6FF;
    }
    .footer-card h3:after{
        background-color: #3ABEF9;
    }
    .footer-card a:hover{
        color: #050C9C;
    }

    .section-6 .sidebar .accordion-item .nav-link {
        color: #001d3d;
    }

    .section-6 .sidebar .accordion-item .nav-link:hover {
        color: #3ABEF9;
    }

    .section-6 .sidebar .nav-link {
        color: #001d3d;
    }

    .section-6 .sidebar .nav-link:hover {
        color: #3ABEF9;
    }
    .section-6 .sidebar .accordion-button:focus {
        box-shadow: none;
        background-color: transparent;
    }
    .section-6 .sidebar .accordion-button:hover {
        color: #3ABEF9;
    }
    header .btn-dark {
        background-color: transparent;
        color: #FFF;
        border: none;
    }
    
    header .btn-dark:hover {
        background-color: transparent;
        color: #3ABEF9;
        outline: none;
        box-shadow: none;
    }
    
    header .btn-dark:active {
        background-color: transparent;
        color: #3ABEF9;
        outline: none;
        box-shadow: none;
    }
    
    header .btn-dark:focus {
        background-color: transparent;
        color: #3ABEF9;
        outline: none;
        box-shadow: none;
    }
    
    header .navbar .nav-link {
        color: #FFF;
        padding: 10px 15px 10px 15px !important;
        font-family: 'Roboto';
    }
    
    header .navbar .nav-link:hover {
        color: #3ABEF9;
    }
    
    header .navbar .dropdown-item:focus {
        color: #3ABEF9;
        background-color: rgba(255, 255, 255, 0.15);
    }
    .section-11 #account-panel .nav-link:hover {
        background-color: #3ABEF9;
    }
    .section-11 .nav-item .nav-link {
        background-color: #3572EF;
        color: #FFF;
        margin-bottom: 5px
    }
</style>
    
<body data-instant-intensity="mousedown">

    <div class="bg-light top-header">
        <div class="container">
            <div class="row align-items-center py-3 d-none d-lg-flex justify-content-between">
                <div class="col-lg-4 logo">
                    <a href="{{ route('front.home') }}" class="text-decoration-none">
                    <span>
                        <img src="{{ asset('front-assets/images/logo-kuyBelanja.png') }}" style="width:200px;">
                    </span>
                    </a>
                </div>
                <div class="col-lg-6 col-6 text-left  d-flex justify-content-end align-items-center">
                    <form action="{{ route('front.shop') }}" method="get">
                        <div class="input-group">
                            <input value="{{ Request::get('search') }}" type="text" placeholder="Cari Produk"
                                class="form-control" name="search" id="search">
                            <button type="submit" class="input-group-text">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </form>
                    @if (Auth::check())
                        <a href="{{ route('account.profile') }}" class="nav-link text-dark">
                            <img src="{{ asset('uploads/profile_images/' . (Auth::user()->profile_image ? Auth::user()->profile_image : 'user-default.png')) }}"
                                class="rounded-circle img-fluid" style="width: 40px; border: 2px solid gray;"
                                id="profileImage" />
                            {{ Auth::user()->name }}
                        </a>
                    @else
                        <a href="{{ route('account.login') }}" class="nav-link text-dark mb-2">Masuk</a>
                        <a href="{{ route('account.register') }}" class="text-dark mb-2">Daftar</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <header class="" style="background-color:#1679AB;">
        <div class="container">
            <nav class="navbar navbar-expand-xl" id="navbar">
                <a href="index.php" class="text-decoration-none mobile-logo">
                <a href="{{ url('index.php') }}" class="text-decoration-none mobile-logo">
                    <span class="image-container">
                        <img src="{{ asset('public/front-assets/images/logo-KuyBelanja (2).png') }}" alt="KuyBelanja Logo">
                    </span>
                </a>
                </a>
                <button class="navbar-toggler menu-btn" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <!-- <span class="navbar-toggler-icon icon-menu"></span> -->
                    <i class="navbar-toggler-icon fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <!-- <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php" title="Products">Home</a>
        </li> -->

                        @if (getCategories()->isNotEmpty())
                            @foreach (getCategories() as $category)
                                <li class="nav-item dropdown" >
                                    <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        {{ $category->name }} <!-- berfungsi untuk memanggil dari database kategori -->
                                    </button>
                                    @if ($category->sub_category->isNotEmpty())
                                        <ul class="dropdown-menu dropdown-menu-dark" style="background-color:#102C57;">
                                            @foreach ($category->sub_category as $subCategory)
                                                <li><a class="dropdown-item nav-link"
                                                        href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}">{{ $subCategory->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        @endif

                    </ul>
                </div>
                <div class="d-flex align-items-center pt-2">
                <a href="{{ route('front.home') }}" class="mx-3" id="home-link">
                <i class="fas fa-home icon-home"></i>
                </a>
                <a href="{{ Auth::check() ? route('front.cart') : '#' }}" class="mx-3" id="cart-link">
                    <i class="fas fa-shopping-cart icon-cart"></i>
                </a>
            </div>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-5" style="background:#1679AB;">
        <div class="container pb-5 pt-3">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-card">
                        <h3>Kategori</h3>
                        @foreach (getCategories() as $category)
                            <a href="{{ route('front.shop', [$category->slug]) }}">
                                {{ $category->name }}
                            </a>
                        @endforeach

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="footer-card">
                        <h3>Tautan Penting</h3>
                        <ul>
                            @if (staticPages()->isNotEmpty())
                                @foreach (staticPages() as $page)
                                    <li><a href="{{ route('front.page', $page->slug) }}"
                                            title="{{ $page->name }}">{{ $page->name }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="footer-card">
                        <h3>Akun saya</h3>
                        <ul>
                            <li><a href="{{ route('account.login') }}" title="Sell">Masuk</a></li>
                            <li><a href="{{ route('account.register') }}" title="Advertise">Daftar</a></li>
                            <li><a href="{{ Auth::check() ? route('front.cart') : '#' }}" id="orders"
                                    title="Contact Us">Pesananku</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area" style="background:#102C57;">
            <div class="container">
                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="copy-right text-center">
                            <strong>&copy; {{ date('Y') }} KuyBelanja</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Wishlist Modal -->
    <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Berhasil</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('front-assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/instantpages.5.1.0.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/lazyload.17.6.0.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/ion.rangeSlider.min.js') }}"></script>
    <script src="{{ asset('front-assets/js/custom.js') }}"></script>
    <script>
        window.onscroll = function() {
            myFunction()
        };

        var navbar = document.getElementById("navbar");
        var sticky = navbar.offsetTop;

        function myFunction() {
            if (window.pageYOffset >= sticky) {
                navbar.classList.add("sticky")
            } else {
                navbar.classList.remove("sticky");
            }
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function addToCart(id) {
            $.ajax({
                url: '{{ route('front.addToCart') }}',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        window.location.href = "{{ route('front.cart') }}";
                    } else {
                        alert(response.message);
                    }
                }
            });
        }

        function addToWishList(id) {
            $.ajax({
                url: '{{ route('front.addToWishlist') }}',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {

                        $("#wishlistModal .modal-body").html(response.message);
                        $("#wishlistModal").modal('show');
                    } else {
                        window.location.href = "{{ route('account.login') }}";
                        // alert(response.message);
                    }
                }
            });
        }
        document.getElementById('cart-link').addEventListener('click', function(event) {
            // Check apakah pengguna sudah login
            var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

            // Jika pengguna belum login, tampilkan SweetAlert2 dan hentikan aksi default
            if (!isLoggedIn) {
                event.preventDefault(); // Menghentikan tindakan bawaan dari anchor tag

                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap login terlebih dahulu!',
                    confirmButtonText: 'OK'
                });
            }
        });
        document.getElementById('orders').addEventListener('click', function(event) {
            // Check apakah pengguna sudah login
            var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

            // Jika pengguna belum login, tampilkan SweetAlert2 dan hentikan aksi default
            if (!isLoggedIn) {
                event.preventDefault(); // Menghentikan tindakan bawaan dari anchor tag

                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap login terlebih dahulu!',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>

    @yield('customJs')
</body>

</html>
