<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

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
}
