<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Wishlist;
use App\Models\Country;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordEmail;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.login');
    }

    public function register()
    {
        return view('front.account.register');
    }

    public function processRegister(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
            'phone' => 'required|numeric',
        ], [
            'name.required' => 'Harap isi nama terlebih dahulu',
            'name.min' => 'Nama minimal harus terdiri dari 3 karakter',
            'email.required' => 'Harap isi email terlebih dahulu',
            'email.email' => 'format alamat email tidak valid.',
            'email.unique' => 'Email sudah ada, harap gunakan email yang berbeda',
            'password.required' => 'Harap isi password terlebih dahulu',
            'password.min' => 'Password minimal 5 karakter',
            'phone.required' => 'Harap isi nomor telepon terlebih dahulu',
            'phone.numeric' => 'Nomor telepon harus berupa angka',
            // 'password.confirmed' => 'Konfirmasi password tidak sesuai.'
        ]);


        if ($validator->passes()) {

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success', 'Registrasi Anda Berhasil');
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

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5',
        ], [
            'email.required' => 'Harap diisi terlebih dahulu.',
            'email.email' => 'Harap isi dengan format email yang benar.',
            'password.required' => 'Harap isi password terlebih dahulu',
            'password.min' => 'Password minimal 5 karakter',
        ]);


        if ($validator->passes()) {

            if (Auth::attempt(
                ['email' => $request->email, 'password' => $request->password],
                $request->get('remember')
            )) {

                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }

                return redirect()->route('account.profile');
            } else {
                //session()->flash('error', 'Either email/password is incorrect.');
                return redirect()->route('account.login')
                    ->withInput($request->only('email'))
                    ->with('error', 'Email atau kata sandi salah');
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile()
    {

        $userId = Auth::user()->id;
        $countries = Country::orderBy('name', 'ASC')->get();
        $user = User::where('id', $userId)->first();
        $address = CustomerAddress::where('user_id', $userId)->first();

        return view('front.account.profile', [
            'user' => $user,
            'countries' => $countries,
            'address' => $address
        ]);
    }

    public function updateProfile(Request $request)
    {
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $userId . ',id',
            'phone' => 'required'
        ]);

        if ($validator->passes()) {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            session()->flash('success', 'Profile berhasil diupdate');

            return response()->json([
                'status' => true,
                'message' => 'Profile berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateAddress(Request $request)
    {
        $userId = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:1',
            'last_name' => 'required',
            'email' => 'required|email',
            'country_id' => 'required',
            'address' => 'required|min:10',
            'city' => 'required',
            'state' => 'required',
            'apartment' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ]);

        if ($validator->passes()) {
            // $user = User::find($userId);
            // $user->name = $request->name;
            // $user->email = $request->email;
            // $user->phone = $request->phone;
            // $user->save();

            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'country_id' => $request->country_id,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip
                ]
            );

            session()->flash('success', 'Alamat berhasil diupdate');

            return response()->json([
                'status' => true,
                'message' => 'Profile berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')
            ->with('success', 'Anda berhasil logout!');
    }

    public function orders()
    {

        $data = [];
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $data['orders'] = $orders;
        return view('front.account.order', $data);
    }


    public function orderDetail($id)
    {
        $data = [];
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('id', $id)->first();
        $data['order'] = $order;

        $orderItems = OrderItem::where('order_id', $id)->get();
        $data['orderItems'] = $orderItems;

        $orderItemsCount = OrderItem::where('order_id', $id)->count();
        $data['orderItemsCount'] = $orderItemsCount;

        return view('front.account.order-detail', $data);
    }

    public function wishlist()
    {
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with('product')->get();
        $data = [];
        $data['wishlists'] = $wishlists;

        return view('front.account.wishlist', $data);
    }

    public function removeProductFromWishList(Request $request)
    {
        $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
        if ($wishlist == null) {

            session()->flash('error', 'Product sudah dihapus');
            return response()->json([
                'status' => true,
            ]);
        } else {
            Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->delete();
            session()->flash('success', 'Product berhasil dihapus');
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function showchangePasswordForm(){
        return view('front.account.change-password');
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);

        if ($validator->passes()) {

            $user = User::select('id','password')->where('id', Auth::user()->id)->first();

            if(!Hash::check($request->old_password, $user->password)){
                session()->flash('error', 'Password lama tidak sesuai, Silahkan coba lagi.');

                return response()->json([
                    'status' => true,            
                ]);
            }

            User::where('id',$user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            session()->flash('sukses', 'Password berhasil diubah.');

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

    public function forgotPassword() {
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request) {
       $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email'
        ]);

        if ($validator->fails()) {
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
        }

        $token = Str::random(60);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);


        //send Email Here
        $user = User::where('email', $request->email)->first();
        $formData = [
            'token' => $token,
            'user' => $user,
            "mailSubject" => 'Anda telah melakukan permintaan reset password.'
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($formData));

        return redirect()->route('front.forgotPassword')->with('sukses',"Silahkan Cek Inbox untuk reset password." );
    }

    public function resetPassword($token){

       $tokenExist = DB::table('password_reset_tokens')->where('token', $token)->first();

       if ($tokenExist == null){
            return redirect()->route('front.forgotPassword')->with('error', 'invalid request');
       }

        return view('front.account.reset-password',[
            'token' => $token
        ]);
    }

    public function processResetPassword(Request $request)
{
    $token = $request->token;

    // Cari token di tabel password_reset_tokens
    $tokenObj = DB::table('password_reset_tokens')->where('token', $token)->first();

    // Cek apakah token valid
    if ($tokenObj == null) {
        return redirect()->route('front.forgotPassword')->with('error', 'Permintaan tidak valid.');
    }

    // Cari user berdasarkan email dari tokenObj
    $user = User::where('email', $tokenObj->email)->first();

    // Cek apakah user ditemukan
    if (!$user) {
        return redirect()->route('front.forgotPassword')->with('error', 'User tidak ditemukan.');
    }

    // Validasi password
    $validator = Validator::make($request->all(), [
        'new_password' => 'required|min:5',
        'confirm_password' => 'required|same:new_password'
    ]);

    if ($validator->fails()) {
        return redirect()->route('front.resetPassword', $token)->withErrors($validator);
    }

    // Update password user
    $user->update([
        'password' => Hash::make($request->new_password)
    ]);

    // Hapus token dari tabel password_reset_tokens
    DB::table('password_reset_tokens')->where('email', $user->email)->delete();

    // Redirect ke halaman login dengan pesan sukses
    return redirect()->route('account.login')->with('success', 'Berhasil mengubah password.');
}




}
