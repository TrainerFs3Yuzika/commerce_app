@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">Akun Saya</a></li>
                    <li class="breadcrumb-item">Pesananku</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Pesananku</h2>
                        </div>
                        <div class="card-body p-4">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>Orders #</td>
                                        <td>Tanggal Pembelian</td>
                                        <td>Status</td>
                                        <td>Total</td>
                                        <td>Detail Order</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($orders->isNotEmpty())
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    <a href="">{{ $order->id }}</a>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</td>
                                                <td>
                                                    @if ($order->status == 'pending')
                                                        <span class="badge bg-danger">Dikemas</span>
                                                    @elseif ($order->status == 'shipped')
                                                        <span class="badge bg-info">Dikirim</span>
                                                    @elseif ($order->status == 'delivered')
                                                        <span class="badge bg-success">Selesai</span>
                                                    @else
                                                        <span class="badge bg-danger">Di Batalkan</span>
                                                    @endif
                                                </td>
                                                <td>Rp{{ number_format($order->grand_total,  0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('account.orderDetail', $order->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                               </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" style="text-align: center;">Belum Ada Pesanan</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
