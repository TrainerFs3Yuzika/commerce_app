<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;

use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Validator; 


class CartController extends Controller
{
    public function addToCart(Request $request)
    {   
        $product = Product::with('product_images')->find($request->id);

        if($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }
        //Cart::add('293ad', 'Product 1', 1, 9.99);
    
        if(Cart::count() > 0 ){
            //echo "Product already in cart";
            //product found in cart
            //check if this product already in the cart
            // Return as message that product already added in your cart
            // if product not found in the cart, then add product in cart
            
            $cartContent = Cart::content();
            $productAlreadyExist = false;

            foreach ($cartContent as $item) {
                if($item->id == $product->id){
                    $productAlreadyExist = true;
                }
            }

            if($productAlreadyExist == false){
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
                
                $status = true;
                $message ='<strong>' .$product->title. ' </strong> Berhasil ditambahkan ke keranjangmu';
                session()->flash('success', $message);
            } else {
                $status = false;
                $message = $product->title. ' sudah ada di keranjang!';
            }

        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message ='<strong>' .$product->title. ' </strong> Berhasil ditambahkan ke keranjangmu';
        
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    
    }

    public function cart() {
        $cartContent = Cart::content();
         //dd($cartContent);
        $data['cartContent'] = $cartContent;
      return view('front.cart', $data);
    }

    public function updateCart(Request $request ){
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = cart::get($rowId);

        $product = Product::find($itemInfo->id);
        // check qty available in stock
        if($product->track_qty == 'Yes'){
            if($qty <= $product->qty){
                Cart::update($rowId, $qty);
                $message = 'Keranjang berhasil diperbaharui';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = 'Pesananmu berjumlah ('.$qty.') stok tidak Mencukupi!';
                $status = false;
                session()->flash('error', $message);
            }
        }else{
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

    public function deleteItem(Request $request){
        $rowId = $request->rowId;
        $itemInfo = cart::get($rowId);

        if($itemInfo == null){
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

    public function checkout(){

        // jika cart kosong tampilkan halaman cart
        if(Cart::count() == 0){
            return redirect()->route('front.cart');
        }

        //jika user belum login alihkan ke login page
        if(Auth::check() == false){

            if(!session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }
            

            return redirect()->route('account.login');
        
        }

        $customerAddress = CustomerAddress::where('user_id',Auth::user()->id)->first();

        session()->forget('url.intended');

        $countries = Country::orderBy('name', 'ASC')->get();

        return view('front.checkout',[
            'countries' => $countries,
            'customerAddress' => $customerAddress
        ]);
    }

    public function processCheckout(Request $request){

        // step -1 Apply Validation

        $validator = Validator::make($request->all(),[
            'first_name' => 'required|min:1',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required|min:10',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Please fix the errors',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // step -2 save user address

        //$customerAddress = CustomerAddress::find();

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

        if($request->payment_method =='cod'){

            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subTotal(2, '.','');
            $grandTotal = $subTotal+$shipping;

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
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

            //step - 4 store order items in order items table

            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();
            }

            session()->flash('success', 'Kamu Berhasil Memesan');
            
            Cart::destroy();

            
            return response()->json([
                'message' => 'Pesanan berhasil disimpan',
                'orderId' => $order->id,
                'status' => true
         
            ]);

        } else {

        }

    }


    public function thankyou($id) {
        return view('front.thanks',[
            'id' => $id
        ]);
    }

}
