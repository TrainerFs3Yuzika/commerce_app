@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">KuyBelanja</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">{{ $product->title }}</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-7 pt-3 mb-3">
        <div class="container">
            <div class="row ">
                @include('front.account.common.message')
                <div class="col-md-5">
                    <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner bg-light">

                            @if ($product->product_images)
                                @foreach ($product->product_images as $key => $productImage)
                                    ;
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img class="w-100 h-100"
                                            src="{{ asset('uploads/product/large/' . $productImage->image) }}"
                                            alt="Image">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="bg-light right">
                        <h1>{{ $product->title }}</h1>
                        <div class="d-flex mb-3">
                            <div class="star-rating product mt-2" title="">
                                <div class="back-stars">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>

                                    <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <small
                                class="pt-2 ps-1">({{ $product->product_ratings_count > 1
                                    ? $product->product_ratings_count . ' Reviews'
                                    : $product->product_ratings_count . ' Penilaian' }})</small>
                        </div>

                        @if ($product->compare_price > 0)
                            <h2 class="price text-secondary"><del>Rp{{number_format($product->compare_price, 0, ',', '.')}}</del></h2>
                        @endif

                        <h2 class="price ">Rp{{number_format($product->price, 0, ',', '.')}}</h2>

                        {!! $product->short_description !!}
                        <br>

                        <!-- <a href="javascript:void(0);" onclick="addToCart ({{ $product->id }});" class="btn btn-dark"><i class="fas fa-shopping-cart"></i> &nbsp;ADD TO CART</a> -->

                        @if ($product->track_qty == 'Yes')
                            @if ($product->qty > 0)
                                <a class="btn btn-dark" href="javascript:void(0);"
                                    onclick="addToCart ({{ $product->id }});">
                                    <i class="fa fa-shopping-cart"></i> &nbsp;Masuk keranjang
                                </a>
                            @else
                                <a class="btn btn-dark" href="javascript:void(0);">
                                    Stok Habis
                                </a>
                            @endif
                        @else
                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart ({{ $product->id }});">
                                <i class="fa fa-shopping-cart"></i> &nbsp;Masuk keranjang
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="bg-light">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                    data-bs-target="#description" type="button" role="tab" aria-controls="description"
                                    aria-selected="true">Deskripsi</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                                    type="button" role="tab" aria-controls="shipping" aria-selected="false">Pengiriman
                                    &
                                    Kembali</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                                    type="button" role="tab" aria-controls="reviews"
                                    aria-selected="false">Ulasan</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel"
                                aria-labelledby="description-tab">
                                {!! $product->description !!}
                            </div>
                            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                                {!! $product->shipping_returns !!}
                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="col-md-8">
                                    <div class="row">
                                        <form action="" name="productRatingForm" id="productRatingForm"
                                            method="post">
                                            <h3 class="h4 pb-3">Nilai Produk</h3>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="name">Nama</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    placeholder="Nama anda">
                                                <p></p>
                                            </div>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" name="email" id="email"
                                                    placeholder="Email anda">
                                                <p></p>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="rating">Penilaian</label>
                                                <br>
                                                <div class="rating" style="width: 10rem">
                                                    <input id="rating-5" type="radio" name="rating"
                                                        value="5" /><label for="rating-5"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-4" type="radio" name="rating"
                                                        value="4" /><label for="rating-4"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-3" type="radio" name="rating"
                                                        value="3" /><label for="rating-3"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-2" type="radio" name="rating"
                                                        value="2" /><label for="rating-2"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-1" type="radio" name="rating"
                                                        value="1" /><label for="rating-1"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                </div>
                                                <p class="product-rating-error text-danger"></p>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="">Nilai kepuasan terhadap pesananmu</label>
                                                <textarea name="comment" id="review" class="form-control" cols="30" rows="10"
                                                    placeholder="Bagaimana kepuasan Anda secara keseluruhan?"></textarea>
                                                <p></p>
                                            </div>
                                            <div>
                                                <button class="btn btn-dark">Kirim</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <div class="overall-rating mb-3">
                                        <div class="d-flex">
                                            <h1 class="h3 pe-3">{{ $avgRating }}</h1>
                                            <div class="star-rating mt-2" title="">
                                                <div class="back-stars">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    @if ($product->product_ratings->isNotEmpty())
                                                        @foreach ($product->product_ratings as $rating)
                                                            {{-- @php 
                                                                    $ratingPer = ($rating->rating*100)/5;

                                                                @endphp --}}
                                                            <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="pt-2 ps-2">
                                                ({{ $product->product_ratings_count > 1
                                                    ? $product->product_ratings_count . ' Reviews'
                                                    : $product->product_ratings_count . ' Penilaian' }})
                                            </div>
                                        </div>

                                    </div>

                                    @if ($product->product_ratings->isNotEmpty())
                                        @foreach ($product->product_ratings as $rating)
                                            @php
                                                $ratingPer = ($rating->rating * 100) / 5;

                                            @endphp
                                            <div class="rating-group mb-4">
                                                <span> <strong>{{ $rating->username }} </strong></span>
                                                <div class="star-rating mt-2" title="">
                                                    <div class="back-stars">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>

                                                        <div class="front-stars" style="width: {{ $ratingPer }}%">
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="my-3">
                                                    <p>{{ $rating->comment }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (!empty($relatedProducts))
        <section class="pt-5 section-8">
            <div class="container">
                <div class="section-title">
                    <h2>Produk Terkait</h2>
                </div>
                <div class="col-md-12">
                    <div id="related-products" class="carousel">
                        @foreach ($relatedProducts as $relProduct)
                            @php
                                $productImage = $relProduct->product_images->first();
                            @endphp

                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="" class="product-img">
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
                                        <!-- <a class="btn btn-dark" href="#">   -->
                                        <!-- <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart ({{ $product->id }});">
                                                                                                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                                                                                                        </a>       -->

                                        @if ($relProduct->track_qty == 'Yes')
                                            @if ($relProduct->qty > 0)
                                                <a class="btn btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart ({{ $relProduct->id }});">
                                                    <i class="fa fa-shopping-cart"></i> Masuk keranjang
                                                </a>
                                            @else
                                                <a class="btn btn-dark" href="javascript:void(0);">
                                                    Stok Habis
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);"
                                                onclick="addToCart ({{ $relProduct->id }});">
                                                <i class="fa fa-shopping-cart"></i> Masuk keranjang
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="">{{ $relProduct->title }}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>Rp{{number_format( $relProduct->price, 0, ',', '.') }}</strong></span>
                                        @if ($relProduct->compare_price > 0)
                                            <br><span class="h6 text-underline"><del>Rp{{number_format( $relProduct->compare_price, 0, ',', '.') }}</del></span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

{{-- @section('customJs')
@endsection --}}

@section('customJs')
    <script type="text/javascript">
        $("#productRatingForm").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: '{{ route('front.saveRating', $product->id) }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    var errors = response.errors;

                    if (response.status == false) {
                        if (errors.name) {
                            $("#name").addClass('is-invalid')
                                .siblings("p")
                                .addClass('invalid-feedback')
                                .html(errors.name);
                        } else {
                            $("#name").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass('invalid-feedback')
                                .html('');
                        }

                        if (errors.email) {
                            $("#email").addClass('is-invalid')
                                .siblings("p")
                                .addClass("invalid-feedback")
                                .html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass("invalid-feedback")
                                .html('');
                        }

                        if (errors.comment) {
                            $("#comment").addClass('is-invalid')
                                .siblings("p")
                                .addClass("invalid-feedback")
                                .html(errors.comment);
                        } else {
                            $("#comment").removeClass('is-invalid')
                                .siblings("p")
                                .removeClass("invalid-feedback")
                                .html('');
                        }

                        if (errors.rating) {
                            $(".product-rating-error").html(errors.rating);
                        } else {
                            $(".product-rating-error").html('');
                        }
                    } else {
                        window.location.href = "{{ route('front.product', $product->slug) }}";
                    }
                }

            })
        });

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
