@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order Id: Ke-{{ $order->id }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">Kembali</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-9">
                    @include('admin.message')
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <h1 class="h5 mb-3">Alamat pengiriman</h1>
                                    <address>
                                        <strong>Nama Lengkap:
                                            {{ $order->first_name . ' ' . $order->last_name }}</strong><br>
                                        Alamat: {{ $order->address }}, {{ $order->city }}, {{ $order->zip }},
                                        {{ $order->countryName }}<br>
                                        Telpon: {{ $order->mobile }}<br>
                                        Email: {{ $order->email }}
                                    </address>
                                    <strong>Tanggal pengiriman</strong><br>
                                    @if (!empty($order->shipped_date))
                                        {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}
                                    @else
                                        n/a
                                    @endif
                                </div>

                                <div class="col-sm-4 invoice-col">
                                    <!-- <b>Invoice #007612</b><br>
                                                                                                                                                                                                                <br> -->
                                    <b>Order ID:</b> {{ $order->id }}<br>
                                    <b>Total:</b> @rupiah($order->grand_total)<br>
                                    <b>Status:</b>
                                    @if ($order->status == 'pending')
                                        <span class="text-danger">Tertunda</span>
                                    @elseif ($order->status == 'shipped')
                                        <span class="text-info">Dikirim</span>
                                    @elseif ($order->status == 'delivered')
                                        <span class="text-success">Terkirim</span>
                                    @else
                                        <span class="text-danger">Dibatalkan</span>
                                    @endif
                                    <br>
                                </div>
                            </div>
                        </div>

                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>@rupiah($item->price)</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>@rupiah($item->total)</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <th colspan="3" class="text-right">Subtotal:</th>
                                        <td>Rp.{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Diskon:
                                            {{ !empty($order->coupon_code) ? '(' . $order->coupon_code . ')' : '' }}
                                        </th>
                                        <td>@rupiah($order->discount)</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Pengiriman:</th>
                                        <td>@rupiah($order->shipping)</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">Jumlah Bayar:</th>
                                        <td>@rupiah($order->grand_total)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Status pemesanan</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                            Tertunda</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                            Dikirim</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                            Terkirim</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Dibatalkan</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="">Tanggal pengiriman</label>
                                    <input placeholder="Tanggal pengiriman" value="{{ $order->shipped_date }}"
                                        type="text" name="shipped_date" id="shipped_date" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary">Ubah</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" name="sendInvoiceEmail" id="sendInvoiceEmail">
                                <h2 class="h4 mb-3">Kirim Email Inovice</h2>
                                <div class="mb-3">
                                    <select name="userType" id="userType" class="form-control">
                                        <option value="customer">Customer</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary">Kirim</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $(document).ready(function() {
            $('#shipped_date').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
        });

        $("#changeOrderStatusForm").submit(function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Anda yakin akan mengubah order status?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, saya yakin!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('orders.changeOrderStatus', $order->id) }}',
                        type: 'post',
                        data: $(this).serializeArray(),
                        dataType: 'json',
                        success: function(response) {
                            window.location.href = '{{ route('orders.detail', $order->id) }}';
                        }
                    });
                }
            });
        });

        $("#sendInvoiceEmail").submit(function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Anda yakin akan mengirim email?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, saya yakin!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('orders.sendInvoiceEmail', $order->id) }}',
                        type: 'post',
                        data: $(this).serializeArray(),
                        dataType: 'json',
                        success: function(response) {
                            window.location.href = '{{ route('orders.detail', $order->id) }}';
                        }
                    });
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
