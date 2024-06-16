<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px">

    @if (isset($mailData['userType']) && $mailData['userType'] == 'customer')
        <h1>Terima kasih atas pesanan Anda!</h1>
        <h2>Nomor Id Order kamu adalah: #{{ $mailData['order']->id }}</h2>
    @elseif (isset($mailData['userType']) && $mailData['userType'] == 'admin')
        <h1>Anda telah menerima pesanan!</h1>
        <h2>Order Id: #{{ $mailData['order']->id }}</h2>
    @endif

    @if(isset($mailData['order']))
        <h2>Alamat Pengiriman</h2>
        <address>
            <strong>{{ $mailData['order']->first_name . ' ' . $mailData['order']->last_name }}</strong><br>
            {{ $mailData['order']->address }}<br>
            {{ $mailData['order']->city }}, {{ $mailData['order']->zip }}, {{ getCountryInfo($mailData['order']->country_id)->name }}<br>
            Telp: {{ $mailData['order']->mobile }}<br>
            Email: {{ $mailData['order']->email }}
        </address>

        <h2>Product</h2>

        <table cellpadding="3" cellspacing="3" border="3" width="700">
            <thead>
                <tr style="background: #CCC; margin:5px;">
                    <th width="300px">Produk</th>
                    <th>Harga</th>
                    <th width="100px">Jumlah</th>                                        
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($mailData['order']->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>@rupiah($item->price)</td>
                    <td>{{ $item->qty }}</td>
                    <td>@rupiah($item->total)</td>
                    </tr>
            @endforeach           
                       
            <br><br>
                <tr>
                    <th colspan="3" align="right">Subtotal:</th>
                    <td>Rp {{ number_format($mailData['order']->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th colspan="3" align="right">Diskon: {{ (!empty($mailData['order']->coupon_code)) ? '(' . $mailData['order']->coupon_code . ')' : '' }}</th>
                    <td>Rp {{ number_format($mailData['order']->discount, 0, ',', '.') }}</td>
                </tr>
                                                    
                <tr>
                    <th colspan="3" align="right">Biaya Pengiriman:</th>
                    <td>Rp {{ number_format($mailData['order']->shipping, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th colspan="3" align="right">Total pembayaran:</th>
                    <td>Rp {{ number_format($mailData['order']->grand_total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>			
    @endif
</body>
</html> 
