<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px">

    @if ($mailData['userType'] == 'customer')
    <h1>Thanks for your order!</h1>
    <h2>Nomor Id Order kamu adalah: #{{ $mailData['order']->id }}</h2>
    @else
    <h1>You have received an order!</h1>
    <h2>Order Id: #{{ $mailData['order']->id }}</h2>
    @endif
    <h2>Shipping Address</h2>
    <address>
        <strong>{{ $mailData['order']->first_name. ' '.$mailData['order']->last_name }}</strong><br>
        {{$mailData['order']->address}}<br>
        {{$mailData['order']->city}}, {{$mailData['order']->zip}}, {{getCountryInfo($mailData['order']->country_id)->name}}<br>
        Telp: {{$mailData['order']->mobile}}<br>
        Email: {{$mailData['order']->email}}
    </address>

    <h2>Product</h2>

    <table cellpadding="3" cellspacing="3" border="3" width="700">
        <thead>
            <tr style="background: #CCC; margin:5px;">
                <th width="300px">Product</th>
                <th>Price</th>
                <th width="100px">Quantity</th>                                        
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($mailData['order']->items as $item)
            <tr>
                <td>{{$item->name}}</td>
                <td>Rp.{{number_format($item->price,2)}}</td>                                        
                <td align="center">{{$item->qty}}</td>
                <td>Rp.{{number_format($item->total,2)}}</td>                        
            </tr>
        @endforeach           
                   
        <br><br>
            <tr>
                <th colspan="3" align="right">Subtotal:</th>
                <td>Rp.{{number_format($mailData['order']->subtotal,2)}}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Discount: {{ (!empty($order->coupon_code)) ? 
                '('.$order->coupon_code.')' : ''}}</th>
                <td>Rp.{{number_format($mailData['order']->discount,2)}}</td>
            </tr>
                                                
            <tr>
                <th colspan="3" align="right">Shipping:</th>
                <td>Rp.{{number_format($mailData['order']->shipping,2)}}</td>
            </tr>
            <tr>
                <th colspan="3" align="right">Grand Total:</th>
                <td>Rp.{{number_format($mailData['order']->grand_total,2)}}</td>
            </tr>
        </tbody>
    </table>			
</body>
</html>