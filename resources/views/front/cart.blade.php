@extends('front.layouts.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">KuyBelanja</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">Keranjang</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-9 pt-4">
        <div class="container">
            <div class="row">
                @if (Session::has('success'))
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {!! Session::get('success') !!}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if (Session::has('error'))
                    <div class="col-md-12">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                {{-- @if (Cart::count() > 0) --}}
                @if (count($cartContent) > 0)
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table" id="cart">
                                <thead>
                                    <tr>
                                        <th>Barang</th>
                                        <th>Harga</th>
                                        <th>Kuantitas</th>
                                        <th>Total</th>
                                        <th>Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @foreach ($cartContent as $item)
                                        @php
                                            $grandTotal += $item->price * $item->qty;
                                        @endphp
                                        <tr>
                                            <td class="text-start">
                                                <div class="d-flex align-items-center">
                                                    {{-- @if (!empty($item->options->productImage->image)) --}}
                                                    @if (!empty($item->product->product_images))
                                                        <img
                                                            src="{{ asset('uploads/product/small/' . $item->product->product_images[0]->image) }}" />
                                                    @else
                                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}" />
                                                    @endif

                                                    <h2>{{ $item->name }}</h2>
                                                </div>
                                            </td>
                                            <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td>
                                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub"
                                                            data-id="{{ $item->product_id }}">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text"
                                                        class="form-control form-control-sm  border-0 text-center"
                                                        value="{{ $item->qty }}">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add"
                                                            data-id="{{ $item->product_id }}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Rp{{number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="deleteItem('{{ $item->product_id }}');"><i
                                                        class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card cart-summery">

                            <div class="card-body">
                                <div class="sub-title">
                                    <h2 class="bg-white">Ringkasan Keranjang</h3>
                                </div>
                                <div class="d-flex justify-content-between pb-2">
                                    <div>Subtotal</div>
                                    {{-- <div>Rp{{ Cart::subtotal() }}</div> --}}
                                    <div>Rp{{ number_format($grandTotal, 0, '', '.') }}</div>
                                </div>
                                <div class="pt-5">
                                    <a href="{{ route('front.checkout') }}" id="checkoutButton"
                                        class="btn-dark btn btn-block w-100">Lanjutkan ke pembayaran</a>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="input-group apply-coupan mt-4">
                        <input type="text" placeholder="Coupon Code" class="form-control">
                        <button class="btn btn-dark" type="button" id="button-addon2">Apply Coupon</button>
                    </div> --}}
                    </div>
                @else
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <h4>Keranjangmu Kosong Nih :( Isi Dong</h4>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script>
        $('.add').click(function() {
            var qtyElement = $(this).parent().prev(); // Qty Input
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue < 10) {
                qtyElement.val(qtyValue + 1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty)
            }
        });

        $('.sub').click(function() {
            var qtyElement = $(this).parent().next();
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue - 1);
                var rowId = $(this).data('id');
                var newQty = qtyElement.val();
                updateCart(rowId, newQty)
            }
        });

        function updateCart(rowId, qty) {
            $.ajax({
                url: '{{ route('front.updateCart') }}',
                type: 'post',
                data: {
                    rowId: rowId,
                    qty: qty
                },
                dataType: 'json',
                success: function(response) {
                    window.location.href = "{{ route('front.cart') }}";
                }


            })
        }

        function deleteItem(rowId) {
            // SweetAlert confirmation
            Swal.fire({
                title: 'Apakah anda yakin ingin hapus ini keranjang?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Ajax request to delete item
                    $.ajax({
                        url: '{{ route('front.deleteItem.cart') }}',
                        type: 'post',
                        data: {
                            rowId: rowId,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Redirect to cart page after successful deletion
                            window.location.href = '{{ route('front.cart') }}';
                        },
                    });
                }
            });
        }
        document.getElementById('checkoutButton').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior
            Swal.fire({
                title: 'Apa kamu yakin?',
                text: " Apakah anda ingin checkout barang ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, checkout!',
                cancelButtonText: 'Tidak, Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        "{{ route('front.checkout') }}"; // Redirect to the checkout route if confirmed
                }
            });
        });
    </script>
@endsection
