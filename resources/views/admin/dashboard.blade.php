@extends('admin.layouts.app')

@section('content')
    <!-- Header Konten (Header Halaman) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </section>
    
    <!-- Konten Utama -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Kotak Statistik -->
                @php
                    $stats = [
                        ['title' => 'Total Pesanan', 'value' => $totalOrders, 'icon' => 'fas fa-shopping-cart', 'link' => route('orders.index')],
                        ['title' => 'Total Produk', 'value' => $totalProducts, 'icon' => 'fas fa-box', 'link' => route('products.index')],
                        ['title' => 'Total Pelanggan', 'value' => $totalCustomers, 'icon' => 'fas fa-users', 'link' => route('users.index')],
                        ['title' => 'Total Penjualan', 'value' => $totalRevenue, 'icon' => 'fas fa-dollar-sign', 'link' => 'javascript:void(0);', 'no_link' => true],
                        ['title' => 'Pendapatan Bulan Ini', 'value' => $revenueThisMonth, 'icon' => 'fas fa-calendar-alt', 'link' => 'javascript:void(0);', 'no_link' => true],
                        ['title' => 'Pendapatan Bulan Lalu ('.$lastMonthName.')', 'value' => $revenueLastMonth, 'icon' => 'fas fa-calendar-alt', 'link' => 'javascript:void(0);', 'no_link' => true],
                        ['title' => 'Pendapatan 30 Hari Terakhir', 'value' => $revenueLastThirtyDays, 'icon' => 'fas fa-calendar-alt', 'link' => 'javascript:void(0);', 'no_link' => true]
                    ];
                @endphp
                
                @foreach ($stats as $stat)
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $stat['value'] }}</h3>
                                <p>{{ $stat['title'] }}</p>
                            </div>
                            <div class="icon">
                                <i class="{{ $stat['icon'] }}"></i>
                            </div>
                            @if (empty($stat['no_link']))
                                <a href="{{ $stat['link'] }}" class="small-box-footer">
                                    Info lebih lanjut <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Grafik -->
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pendapatan (30 Hari Terakhir)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pesanan (30 Hari Terakhir)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="ordersChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');

        // Gradient untuk Grafik Pendapatan
        const revenueGradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
        revenueGradient.addColorStop(0, 'rgba(75, 192, 192, 0.4)');
        revenueGradient.addColorStop(1, 'rgba(75, 192, 192, 0.1)');

        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($revenueLabels),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($revenueData),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    backgroundColor: revenueGradient,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.formattedValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                }
            }
        });

        // Gradient untuk Grafik Pesanan
        const ordersGradient = ordersCtx.createLinearGradient(0, 0, 0, 400);
        ordersGradient.addColorStop(0, 'rgba(153, 102, 255, 0.4)');
        ordersGradient.addColorStop(1, 'rgba(153, 102, 255, 0.1)');

        const ordersChart = new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: @json($orderLabels),
                datasets: [{
                    label: 'Pesanan',
                    data: @json($orderData),
                    backgroundColor: ordersGradient,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1,
                    hoverBackgroundColor: 'rgba(153, 102, 255, 0.6)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.formattedValue + ' pesanan';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection