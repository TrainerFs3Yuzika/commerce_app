<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class FrontController extends Controller
{
    public function index()
    {

        $products = Product::where('is_featured', 'Yes')
            ->orderBy('id', 'DESC')
            ->take(8)
            ->where('status', 1)->get();

        $data['featuredProducts'] = $products;

        $latestProducts = Product::orderBy('id', 'DESC')
            ->where('status', 1)->take(8)->get();

        $data['latestProducts'] = $latestProducts;
        return view('front.home', $data);
    }

    public function addToWishlist(Request $request)
    {
        if (Auth::check() == false) {

            session(['url.intended' => url()->previous()]);

            return response()->json([
                'status' => false
            ]);
        }

        $product = Product::where('id', $request->id)->first();

        if ($product == null) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">>Product tidak ditemukan</div>'
            ]);
        }

        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ]
        );

        // $wishlist = new Wishlist;
        // $wishlist->user_id = Auth::user()->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();


        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"' . $product->title . '"</strong> telah ditambahkan ke wishlist</div>'
        ]);
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($page == null) {
            abort(404);
        }
        return view('front.page', [
            'page' => $page
        ]);
    }

    public function sendContactEmail(Request $request)
    {
        // dd($request);
    
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required|min:10'
        ], [
            'name.required' => 'Harap isi nama terlebih dahulu',
            'email.required' => 'Harap isi email terlebih dahulu',
            'email.email' => 'Format alamat email tidak valid.',
            'subject.required' => 'Harap isi subjek terlebih dahulu',
            'subject.min' => 'Subjek harus memiliki setidaknya 10 karakter',
        ]);
    
        if ($validator->passes()) {
    
            //send email here
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'mail_subject' => 'Kamu telah menerima kontak email.',
            ];
    
            // Mengirim email ke alamat statis
            $adminEmail = 'kuyyybelanjaaa@gmail.com';
    
            Mail::to($adminEmail)->send(new ContactEmail($mailData));
    
            session()->flash('sukses', 'Terimakasih sudah kontak kami, kami akan segera memberikan feedback');
    
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    
}