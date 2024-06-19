@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Pesanan</h1>
                </div>
                <div class="col-sm-6 text-right">
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <div class="card">
                <form action="" method="GET">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('orders.index') }}'"
                                class="btn btn-default btn-sm">Reset</button>
                        </div>
                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                    class="form-control float-right" placeholder="Cari Pesanan">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body table-responsive p-0">

                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Pelanggan</th>
                                <th>Email</th>
                                <th>Telpon</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Tanggal Pembelian</th>
                                <th>Detail Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->isNotEmpty())
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>@formatPhone($order->mobile)</td>
                                        <td>
                                            @if ($order->status == 'paid')
                                                        <span class="badge bg-success">Dibayar</span>
                                                    @elseif ($order->status == 'pending')
                                                        <span class="badge bg-warning">Belum Dibayar</span>
                                                    @elseif ($order->status == 'shipped')
                                                        <span class="badge bg-info">Dikirim</span>
                                                    @elseif ($order->status == 'delivered')
                                                        <span class="badge bg-success">Selesai</span>
                                                    @else
                                                        <span class="badge bg-danger">Dibatalkan</span>
                                                    @endif
                                        </td>
                                        <td>@rupiah($order->grand_total)</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y H:i:s') }}
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('orders.detail', [$order->id]) }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" style="text-align: center;">Data Tidak Tersedia</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
@endsection
