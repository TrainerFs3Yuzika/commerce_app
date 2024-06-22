<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Bulanan - Bulan {{ Carbon\Carbon::create()->month($month)->isoFormat('MMMM') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(241, 245, 249);
        }
        .w-full {
            width: 100%;
        }
        .w-half {
            width: 50%;
        }
        .total {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
        .w-full {
            width: 100%;
        }
        .w-half {
            width: 50%;
        }
        .margin-top {
            margin-top: 1.25rem;
        }
        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(241, 245, 249);
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        table.products {
            font-size: 0.875rem;
        }
        table.products tr {
            background-color: rgb(96, 165, 250);
        }
        table.products th {
            text-align: left;
            color: #ffffff;
            padding: 0.5rem;
        }
        table tr.items {
            background-color: rgb(241, 245, 249);
        }
        table tr.items td {
            padding: 0.5rem;
        }
        .total {
            text-align: right;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <h1 style="color:rgb(96, 165, 250); margin-bottom: 50px;"><strong>KuyBelanja</strong></h1>
    <h2 class="text-center">Laporan Penjualan Bulanan - Bulan {{ Carbon\Carbon::create()->month($month)->isoFormat('MMMM') }}</h2>
    <table class="products">
            <tr>
                <th>Order ID</th>
                <th>Pelanggan</th>
                <th>Email</th>
                <th>Total</th>
                <th>Tanggal Pembelian</th>
            </tr>
            @foreach ($orders as $order)
            <tr class="items">
                <td>{{ $order->id }}</td>
                <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                <td>{{ $order->email }}</td>
                <td>{{ number_format($order->grand_total, 0, ',', '.') }}</td>
                <td>{{ $order->created_at->format('d M Y H:i:s') }}</td>
            </tr>
            @endforeach

        <!-- Menampilkan total keseluruhan pendapatan -->
        <tfoot>
            <tr>
                <td colspan="2"></td>
                <th>Total Keseluruhan Pendapatan:</th>
                <td colspan ="3" style="color:white;"><strong>{{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Sertakan gambar grafik -->
    {!! $chartsHtml !!}

    <div class="footer margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div> Copyright</div>
                    <div>&copy; KuyBelanja</div>
                </td>
                <td class="total">
                    <div>Terakhir diunduh: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</div>

                </td>
            </tr>
        </table>
    </div>

    <!-- Script untuk menggambar grafik menggunakan Lavacharts -->
    <script src="https://cdn.jsdelivr.net/npm/lavacharts"></script>
    <script>
        // Inisialisasi grafik pendapatan bulanan
        const revenueData = @json($revenueMonthlyData);
        const revenueLabels = @json($revenueMonthlyLabels);

        let revenueChart = new Lava.LineChart('revenueChart');
        revenueChart.DataTable.addDateColumn('Bulan');
        revenueChart.DataTable.addNumberColumn('Pendapatan');

        revenueLabels.forEach((label, index) => {
            revenueChart.DataTable.addRow([new Date(label), revenueData[index]]);
        });

        revenueChart.draw();

        // Inisialisasi grafik jumlah pesanan bulanan
        const ordersData = @json($orderMonthlyData);
        const ordersLabels = @json($orderMonthlyLabels);

        let ordersChart = new Lava.BarChart('ordersChart');
        ordersChart.DataTable.addDateColumn('Bulan');
        ordersChart.DataTable.addNumberColumn('Pesanan');

        ordersLabels.forEach((label, index) => {
            ordersChart.DataTable.addRow([new Date(label), ordersData[index]]);
        });

        ordersChart.draw();
    </script>
</body>
</html>
