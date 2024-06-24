<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail PDF</title>
    <style>
        /* CSS styling untuk PDF bisa ditambahkan di sini */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
   
        h5 {
            margin: 0;
        }
        p{
            margin: 0;
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
    <table class="w-full">
        <tr>
            <td class="w-half">
                <h1 style="color:rgb(96, 165, 250);"><strong>KuyBelanja</strong></h1>
            </td>
            
            <td class="total">
                <h2>INVOICE</h2>
            </td>
        </tr>
    </table>

    <!-- Informasi Order -->
    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div><p><strong>ID Pembelian:</strong> {{ $order->id }}</p></div>
                    <div><p><strong>Nama Pembeli:</strong> {{ $order->first_name }} {{ $order->last_name }}</p></div>
                    <div><p><strong>Tanggal Pembelian:</strong> {{ $order->created_at }}</p></div>
                    <div><p><strong>Alamat Pengirim:</strong> {{ $order->address}}</p></div>
                </td>
                <td class="w-half">
                    <div></div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Daftar Produk Pesanan -->
    <div class ="margin-top">
        <h5>Jumlah Barang: ({{ $orderItemsCount }})</h5>
        <table class="products">
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
                @foreach ($orderItems as $item)
                <tr class ="items">
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
        </table>
    </div>

    <!-- Total Pembayaran -->
    <div class="w-half">
        <br>
        <h3>Total Pesanan</h3>
        <p><strong>Subtotal:</strong> Rp{{ number_format($order->subtotal, 0, ',', '.') }}</p>
        <p><strong>Diskon:</strong> {!! $order->coupon_code ? '<strong>('.$order->coupon_code.')</strong>' : '' !!} Rp{{ number_format($order->discount, 0, ',', '.') }}</p>
        <p><strong>Biaya Pengiriman:</strong> Rp{{ number_format($order->shipping, 0, ',', '.') }}</p>
        <p><strong>Total Pembayaran:</strong> Rp{{ number_format($order->grand_total, 0, ',', '.') }}</p>
    </div>

    <div class="footer margin-top">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div> Terima Kasih</div>
                    <div>&copy; KuyBelanja</div>
                </td>
                <td class="total">
                    <div>Terakhir diubah: {{$order->updated_at}} </div>
                </td>
            </tr>
        </table>
        
        
    </div>

</body>
</html>
