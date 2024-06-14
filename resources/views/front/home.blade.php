@extends('front.layouts.app')

@section('content')
    <section class="section-1">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel"
            data-bs-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="video-container">
                        <video id="videoCarousel3" class="d-block w-100" autoplay loop muted>
                            <source src="{{ asset('front-assets/videos/carousel-3.mp4') }}" type="video/mp4">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    </div>
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Diskon 70% untuk Barang Branded</h1>
                            <p class="mx-md-5 px-5">Nikmati diskon 70% untuk barang branded secara online!</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="shop">Kuy Belanja</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item ">
                    <div class="video-container">
                        <video id="videoCarousel1" class="d-block w-100" autoplay loop muted>
                            <source src="{{ asset('front-assets/videos/carousel-1.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Elektronik</h1>
                            <p class="mx-md-5 px-5">Temukan teknologi terkini dengan koleksi elektronik kami! Dari gadget
                                canggih hingga perangkat rumah pintar, penuhi kebutuhan teknologimu di sini.</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="shop">Kuy Belanja</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="video-container">
                        <video id="videoCarousel2" class="d-block w-100" autoplay loop muted>
                            <source src="{{ asset('front-assets/videos/carousel-2.mp4') }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Fashion</h1>
                            <p class="mx-md-5 px-5"> Tetap stylish dengan koleksi fashion terkini kami. Dari kasual chic
                                hingga elegan, temukan gaya yang sempurna untuk setiap momen.
                                Pilih dari pakaian, aksesori, dan sepatu yang akan membuat Anda tampil memukau.</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="shop">Kuy Belanja</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Sebelumnya</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Selanjutnya</span>
            </button>
        </div>
    </section>


    <section class="section-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Produk Berkualitas</h5>
                    </div>
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Gratis Ongkir</h2>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">7 Hari Pengembalian</h2>
                    </div>
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Bayar Di Tempat</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-3">
        <div class="container">
            <div class="section-title">
                <h2>Kategori</h2>
            </div>
            <div class="row pb-3">
                @if (getCategories()->isNotEmpty())
                    @foreach (getCategories() as $category)
                        <div class="col-lg-3">
                            <div class="cat-card">
                                <div class="left">
                                    @if ($category->image != '')
                                        <img src="{{ asset('uploads/category/thumb/' . $category->image) }}" alt=""
                                            class="img-fluid">
                                    @endif
                                    <!-- <img src="{{ asset('front-assets/images/cat-1.jpg') }}" alt="" class="img-fluid"> -->
                                </div>
                                <div class="right">
                                    <div class="cat-data">
                                        <h2>{{ $category->name }}</h2>
                                        <!-- <p>100 Products</p> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Produk Unggulan</h2>
            </div>
            <div class="row pb-3">
                @if ($featuredProducts->isNotEmpty())
                    @foreach ($featuredProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="col-md-3">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href=" {{ route('front.product', $product->slug) }}" class="product-img">

                                        @if (!empty($productImage->image))
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/product/small/' . $productImage->image) }}" />
                                        @else
                                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                                        @endif
                                    </a>
                                    <a onclick="addToWishList({{ $product->id }})" class="whishlist"
                                        href="javascript:void(0);">
                                        <i class="far fa-heart"></i>
                                    </a>

                                    <div class="product-action">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart ({{ $product->id }});">
                                                    <i class="fa fa-shopping-cart"></i> Masuk Keranjang
                                                </a>
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);">
                                                    Stok Habis
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);"
                                                onclick="addToCart ({{ $product->id }});">
                                                <i class="fa fa-shopping-cart"></i> Masuk Keranjang
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link"
                                        href="{{ route('front.product', $product->slug) }}">{{ $product->title }}</a>
                                    <div class="price mt-2">

                                    <span class="h5"><strong>Rp{{ number_format($product->price, 0, ',', '.') }}</strong></span>
                                        @if ($product->compare_price > 0)
                                            <br><span class="h6 text-underline"><del>Rp{{ number_format($product->compare_price, 0, ',', '.')}}</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif


            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Produk Terbaru</h2>
            </div>
            <div class="row pb-3">
                @if ($latestProducts->isNotEmpty())
                    @foreach ($latestProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="col-md-3">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.product', $product->slug) }}" class="product-img">

                                        @if (!empty($productImage->image))
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/product/small/' . $productImage->image) }}" />
                                        @else
                                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                                        @endif
                                    </a>
                                    <a onclick="addToWishList({{ $product->id }})" class="whishlist"
                                        href="javascript:void(0);">
                                        <i class="far fa-heart"></i>
                                    </a>

                                    <div class="product-action">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart ({{ $product->id }});">
                                                    <i class="fa fa-shopping-cart"></i> Masuk Keranjang
                                                </a>
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);">
                                                    Stock Habis
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);"
                                                onclick="addToCart ({{ $product->id }});">
                                                <i class="fa fa-shopping-cart"></i> Masuk Keranjang
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="product.php">{{ $product->title }}</a>
                                    <div class="price mt-2">

                                        <span class="h5"><strong>Rp{{number_format($product->price, 0, ',', '.')}}</strong></span>
                                        @if ($product->compare_price > 0)
                                            <br><span class="h6 text-underline"><del>Rp{{number_format($product->compare_price, 0, ',', '.')}}</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

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
