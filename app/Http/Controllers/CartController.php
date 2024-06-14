<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Carts;
use App\Models\ShippingCharge;
use App\Models\DiscountCoupon;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        if (empty(Auth::user())) {
            $status = false;
            $message = 'Silahkan login masuk terlebih dahulu!';
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        }

        $product = Product::with('product_images')->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }
        $getCart = Carts::where(['product_id' => $request->id, 'user_id' => Auth::user()->id])->first();
        if (empty($getCart)) {

            $payloadInsert = array(
                'product_id' => $request->id,
                'user_id'    => Auth::user()->id,
                'name'       => $product->title,
                'price'      => $product->price,
                'qty'        => 1,
                'created_at' => date('Y-m-d H:i:s')
            );
            Carts::create($payloadInsert);

            $status = true;
            $message = '<strong>' . $product->title . ' </strong> Berhasil ditambahkan ke keranjangmu';
            session()->flash('success', $message);
        } else {
            // $getCart->qty = $getCart->qty + 1;
            // $getCart->save();
            $status = false;
            $message = $product->title . ' sudah ada di keranjang!';
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);

        die;
        //Cart::add('293ad', 'Product 1', 1, 9.99);
        if (Cart::count() > 0) {
            //echo "Product already in cart";
            //product found in cart
            //check if this product already in the cart
            // Return as message that product already added in your cart
            // if product not found in the cart, then add product in cart

            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }

            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

                $status = true;
                $message = '<strong>' . $product->title . ' </strong> Berhasil ditambahkan ke keranjangmu';
                session()->flash('success', $message);
            } else {
                $status = false;
                $message = $product->title . ' sudah ada di keranjang!';
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<strong>' . $product->title . ' </strong> Berhasil ditambahkan ke keranjangmu';

            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart()
    {
        // $cartContent = Cart::content();
        if (empty(Auth::user())) {
            return redirect()->route('account.login');
        }
        $userId = Auth::user()->id;
        $cartContent = Carts::with('product.product_images')->where('carts.user_id', $userId)->get();
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $getCart = Carts::where(['product_id' => $rowId, 'user_id' => Auth::user()->id])->first();
        $product = Product::find($rowId);
        if (empty($getCart)) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                $getCart->qty = $qty;
                $getCart->save();
                $message = 'Keranjang berhasil diperbaharui';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = 'Pesananmu berjumlah (' . $qty . ') stok tidak Mencukupi!';
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            $getCart->qty = $qty;
            $getCart->save();
            $message = 'Keranjang berhasil diperbaharui';
            $status = true;
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);

        die;

        $itemInfo = cart::get($rowId);

        $product = Product::find($itemInfo->id);
        // check qty available in stock
        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Keranjang berhasil diperbaharui';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = 'Pesananmu berjumlah (' . $qty . ') stok tidak Mencukupi!';
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Keranjang berhasil diperbaharui';
            $status = true;
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request)
    {
        if (empty(Auth::user())) {
            $status = false;
            $message = 'Silahkan login masuk terlebih dahulu!';
            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        }

        $checkCart = Carts::where(['product_id' => $request->rowId, 'user_id' => Auth::user()->id])->first();
        if (empty($checkCart)) {
            $errorMessage = 'Item tidak ditemukan dalam keranjang!';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        Carts::where(['product_id' => $request->rowId, 'user_id' => Auth::user()->id])->delete();
        $message = 'Item Berhasil dihapus';
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);

        die;

        $rowId = $request->rowId;
        $itemInfo = cart::get($rowId);

        if ($itemInfo == null) {
            $errorMessage = 'Item tidak ditemukan dalam keranjang!';
            session()->flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }

        Cart::remove($request->rowId);

        $message = 'Item Berhasil dihapus';

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function checkout()
    {

        $discount = 0;

        $cartAll = Carts::where('user_id', Auth::user()->id)->get();
        if (count($cartAll) == 0) {
            return redirect()->route('front.cart');
        }

        if (Auth::check() == false) {

            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

        session()->forget('url.intended');

        $countries = Country::orderBy('name', 'ASC')->get();

        $subTotal = 0;
        foreach ($cartAll as $cart) {
            $subTotal += $cart->price * $cart->qty;
        }

        $countries = Country::orderBy('name', 'ASC')->get();

        //Apply Discount Here
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        // Menghitung biaya shipping
        if ($customerAddress != '') {
            $userCountry = $customerAddress->country_id;
            $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();

            //echo $shippingInfo->amount;

            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;
            foreach ($cartAll as $cart) {
                $totalQty += $cart->qty;
            }

            $totalShippingCharge = $totalQty * $shippingInfo->amount;
            $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
        } else {
            $grandTotal = ($subTotal - $discount);
            $totalShippingCharge = 0;
        }

        return view('front.checkout', [
            'countries' => $countries,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => number_format($discount, 2),
            'grandTotal' => $grandTotal,
            'subTotal'  => $subTotal

        ]);

        die;

        // jika cart kosong tampilkan halaman cart
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        //jika user belum login alihkan ke login page
        if (Auth::check() == false) {

            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }


            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

        session()->forget('url.intended');

        $countries = Country::orderBy('name', 'ASC')->get();

        $subTotal = Cart::subtotal(2, '.', '');

        $countries = Country::orderBy('name', 'ASC')->get();

        //Apply Discount Here
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        // Menghitung biaya shipping
        if ($customerAddress != '') {
            $userCountry = $customerAddress->country_id;
            $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();

            //echo $shippingInfo->amount;

            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            $totalShippingCharge = $totalQty * $shippingInfo->amount;
            $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
        } else {
            $grandTotal = ($subTotal - $discount);
            $totalShippingCharge = 0;
        }

        return view('front.checkout', [
            'countries' => $countries,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => number_format($discount, 2),
            'grandTotal' => $grandTotal

        ]);
    }
    // awal
    public function processCheckout(Request $request)
    {
        // step -1 Apply Validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:1',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required|min:10',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ], [
            'first_name.required' => 'Harap diisi terlebih dahulu.',
            'first_name.min' => 'Harap isi dengan minimal 1 karakter.',
            'last_name.required' => 'Harap diisi terlebih dahulu.',
            'email.required' => 'Harap diisi terlebih dahulu.',
            'email.email' => 'Harap isi dengan format email yang benar.',
            'country.required' => 'Harap dipilih terlebih dahulu.',
            'address.required' => 'Harap diisi terlebih dahulu.',
            'address.min' => 'Harap isi dengan minimal 10 karakter.',
            'city.required' => 'Harap diisi terlebih dahulu.',
            'state.required' => 'Harap diisi terlebih dahulu.',
            'zip.required' => 'Harap diisi terlebih dahulu.',
            'mobile.required' => 'Harap diisi terlebih dahulu.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please fix the errors',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // step -2 save user address
        $user = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip
            ]
        );

        // step - 3 store data in orders table
        $checkCart = Carts::where('user_id', $user->id)->get();
        if ($request->payment_method == 'cod') {
            $discountCodeId = NULL;
            $promoCode = '';
            $shipping = 0;
            $discount = 0;
            $subTotal  = 0;
            foreach ($checkCart as $cart) {
                $subTotal += (int) $cart->price * $cart->qty;
            }

            // Apply Discount Here
            if (session()->has('code')) {
                $code = session()->get('code');
                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }
                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }

            // Menghitung biaya shipping
            $shippingInfo = ShippingCharge::where('country_id', $request->country)->first();
            $totalQty = 0;
            foreach ($checkCart as $cart) {
                $totalQty += $cart->qty;
            }

            if ($shippingInfo != null) {
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shipping;
            } else {
                $shippingInfo = ShippingCharge::where('country_id', 'semua_negara')->first();
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shipping;
            }

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = !empty($discountCodeId) ? $discountCodeId : null;
            $order->coupon_code = $promoCode ?? null;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            $order->country_id = $request->country;
            $order->save();

            // step - 4 store order items in order items table
            foreach ($checkCart as $cart) {
                $orderItem = new OrderItem;
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $cart->product_id;
                $orderItem->name = $cart->name;
                $orderItem->qty = $cart->qty;
                $orderItem->price = $cart->price;
                $orderItem->total = $cart->price * $cart->qty;
                $orderItem->save();

                $productData = Product::find($cart->product_id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;
                    $updatedQty = $currentQty - $cart->qty;
                    $productData->qty = $updatedQty;
                    $productData->save();
                }
            }

            // Send Order Email
            orderEmail($order->id);

            // Menghapus data keranjang setelah checkout berhasil
            Carts::where('user_id', $user->id)->delete();

            session()->flash('success', 'Kamu Berhasil Memesan');

            return response()->json([
                'message' => 'Pesanan berhasil disimpan',
                'orderId' => $order->id,
                'status' => true
            ]);
        } else {
            // Proses pembayaran lain (misalnya pembayaran online) bisa ditambahkan di sini
        }
    }
    // ini adalah akhir

    public function thankyou($id)
    {
        return view('front.thanks', [
            'id' => $id
        ]);
    }

    public function getOrderSummery(Request $request)
    {
        // $subTotal = Cart::subtotal(2, '.', '');
        $subTotal = 0;
        foreach (Carts::where('user_id', Auth::user()->id)->get() as $item) {
            $subTotal += $item->price * $item->qty;
        }
        $discount = 0;
        $discountString = '';

        //Apply Discount Here
        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            $discountString = '<div class="mt-4" id="discount-response">
                <strong>' . session()->get('code')->code . '</strong>
                <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
            </div>';
        }


        if ($request->country_id > 0) {

            $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();

            $totalQty = 0;
            // foreach (Cart::content() as $item) {
            //     $totalQty += $item->qty;
            // }
            foreach (Carts::where('user_id', Auth::user()->id)->get() as $item) {
                $totalQty += $item->qty;
            }


            if ($shippingInfo != null) {

                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => number_format($discount, 2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);
            } else {
                $shippingInfo = ShippingCharge::where('country_id', 'semua_negara')->first();

                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format(($subTotal - $discount), 2),
                    'discount' => number_format($discount, 2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2),
                ]);
            }
        } else {
            $grandTotal = ($subTotal - $discount);
            return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal, 2),
                'discount' => number_format($discount, 2),
                'discountString' => $discountString,
                'shippingCharge' => number_format(0, 2),
            ]);
        }
    }


    public function applyDiscount(Request $request)
    {
        $code = DiscountCoupon::where('code', $request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Kode Kupon Tidak Tersedia!'
            ]);
        }

        $now = Carbon::now();

        if ($code->starts_at != "") {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);

            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kupon Diskon Tidak Tersediaqqq!'
                ]);
            }
        }

        if ($code->expires_at != "") {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kupon Diskon Tidak Berlaku Lagi!'
                ]);
            }
        }

        // Cek Maksimal Penggunaan
        if ($code->max_uses > 0) {
            $couponUsed = Order::where('coupon_code_id', $code->id)->count();

            if ($couponUsed >= $code->max_uses) {
                return response()->json([
                    'status' => false,
                    'message' => 'Yahhh Kupon Diskon Sudah Habis Nih :('
                ]);
            }
        }

        // Cek Maksimal Penggunaan per Pengguna
        if ($code->max_uses_user > 0) {
            $couponUsedByUser = Order::where('coupon_code_id', $code->id)
                ->where('user_id', Auth::user()->id)
                ->count();
            if ($couponUsedByUser >= $code->max_uses_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kupon Sudah Pernah Anda Pakai!'
                ]);
            }
        }

        $cartsCheck = Carts::where('user_id', Auth::user()->id)->get();
        $subTotal = 0;
        foreach ($cartsCheck as $cart) {
            $subTotal += $cart->price * $cart->qty;
        }

        if ($code->min_amount > 0) {
            if ($subTotal < $code->min_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Harga Barangmu Minimal Rp' . $code->min_amount . ' Supaya Dapat Memakai Kupon Ini!',
                ]);
            }
        }
        if ($code->discount_amount > 0) {
            if ($subTotal <= $code->discount_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Harga Barangmu Tidak Boleh Kurang Dari Harga Diskon!',
                ]);
            }
        }

        session()->put('code', $code);
        return $this->getOrderSummery($request);

        die;
        $subTotal = Cart::subtotal(2, '.', '');

        // Cek Kondisi Jumlah Minimum
        if ($code->min_amount > 0) {
            if ($subTotal < $code->min_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Harga Barangmu Minimal Rp' . $code->min_amount . ' Supaya Dapat Memakai Kupon Ini!',
                ]);
            }
        }
        if ($code->discount_amount > 0) {
            if ($subTotal <= $code->discount_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Harga Barangmu Tidak Boleh Kurang Dari Harga Diskon!',
                ]);
            }
        }

        session()->put('code', $code);

        return $this->getOrderSummery($request);
    }



    public function removeCoupon(Request $request)
    {
        session()->forget('code');
        return $this->getOrderSummery($request);
    }
}
