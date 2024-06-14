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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Mail\RegisterEmail;
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
            'password_confirmation' => 'required_with:password|same:password|min:5',
            'phone' => 'required|digits_between:11,13|numeric',
        ], [
            'name.required' => 'Harap isi nama terlebih dahulu',
            'name.min' => 'Nama minimal harus terdiri dari 3 karakter',
            'email.required' => 'Harap isi email terlebih dahulu',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Email sudah ada, harap gunakan email yang berbeda',
            'password.required' => 'Harap isi password terlebih dahulu',
            'password.min' => 'Password minimal 5 karakter',
            'password_confirmation.required_with' => 'Harap isi konfirmasi password',
            'password_confirmation.same' => 'Konfirmasi password tidak sesuai dengan password',
            'password_confirmation.min' => 'Konfirmasi password minimal 5 karakter',
            'phone.required' => 'Harap isi nomor telepon terlebih dahulu',
            'phone.digits_between' => 'Nomor telepon harus terdiri dari 11 sampai 13 digit',
            'phone.numeric' => 'Nomor telepon harus berupa angka',
        ]);

        if ($validator->passes()) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->verify_key = Str::random(40); // Tambahkan ini untuk verifikasi
            $user->save();

            // Kirim email verifikasi
            $details = [
                'name' => $user->name,
                'email' => $user->email,
                'website' => 'KuyBelanja',
                'datetime' => now()->format('d F Y H:i:s'),
                'verification_url' => route('verify', $user->verify_key)
            ];
            Mail::to($request->email)->send(new RegisterEmail($details));

            session()->flash('success', 'Link verifikasi telah dikirim ke email anda. Silahkan cek email anda untuk mengaktifkan akun.');
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

    public function verify($verify_key)
    {
        $keyCheck = User::select('verify_key')
            ->where('verify_key', $verify_key)
            ->exists();

        if ($keyCheck) {
            User::where('verify_key', $verify_key)
                ->update([
                    'active' => 1,
                    'email_verified_at' => now(),
                ]);

            return "Verifikasi berhasil. Akun anda sudah aktif.";
        } else {
            return "Key tidak valid.";
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
            $user = User::where('email', $request->email)->first();

            if ($user) {
                if (!$user->active || is_null($user->email_verified_at)) {
                    return redirect()->route('account.login')
                        ->withInput($request->only('email'))
                        ->with('error', 'Anda belum verifikasi akun. Silakan Cek email Anda');
                }

                if (Auth::attempt(
                    ['email' => $request->email, 'password' => $request->password],
                    $request->get('remember')
                )) {
                    if (session()->has('url.intended')) {
                        return redirect(session()->get('url.intended'));
                    }

                    return redirect()->route('account.profile');
                } else {
                    return redirect()->route('account.login')
                        ->withInput($request->only('email'))
                        ->with('error', 'Email atau kata sandi salah');
                }
            } else {
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
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $userId . ',id',
                'phone' => 'required|digits_between:11,13|numeric',
                'profile_image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'name.required' => 'Harap isi nama terlebih dahulu.',
                'email.required' => 'Harap isi alamat email terlebih dahulu.',
                'email.email' => 'Format alamat email tidak valid.',
                'email.unique' => 'Alamat email sudah digunakan.',
                'phone.required' => 'Harap isi nomor telepon terlebih dahulu.',
                'phone.digits_between' => 'Nomor telepon harus terdiri dari 11 sampai 13 digit',
                'phone.numeric' => 'Nomor telepon harus berupa angka',
            ]
        );

        $customMessages = [
            'profile_image.image' => 'Filenya harus berupa gambar (jpeg, png, jpg, gif, or svg)',
            'profile_image.mimes' => 'File harus bertipe: :values',
            'profile_image.max' => 'Filenya tidak boleh lebih besar dari :max kilobytes',
        ];

        $validator->setCustomMessages($customMessages);

        if ($validator->passes()) {
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;

            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image');
                // Menghapus gambar profil lama jika ada
                if ($user->profile_image && file_exists(public_path('uploads/profile_images/' . $user->profile_image))) {
                    unlink(public_path('uploads/profile_images/' . $user->profile_image));
                }
                $imageName = time() . '.' . $profileImage->getClientOriginalExtension();
                $profileImage->move(public_path('uploads/profile_images'), $imageName);
                $user->profile_image = $imageName;
            }

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

        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|min:1',
                'last_name' => 'required',
                'email' => 'required|email',
                'country_id' => 'required',
                'address' => 'required|min:10',
                'city' => 'required',
                'state' => 'required',
                'apartment' => 'required',
                'zip' => 'required',
                'mobile' => 'required|digits_between:11,13|numeric'
            ],
            [
                'first_name.required' => 'Harap isi nama depan.',
                'first_name.min' => 'Nama depan minimal :min karakter.',
                'last_name.required' => 'Harap isi nama belakang.',
                'email.required' => 'Harap isi alamat email.',
                'email.email' => 'Format alamat email tidak valid.',
                'country_id.required' => 'Harap pilih negara.',
                'address.required' => 'Harap isi alamat lengkap.',
                'address.min' => 'Alamat minimal min 10 karakter.',
                'city.required' => 'Harap isi kota.',
                'state.required' => 'Harap isi provinsi atau negara bagian.',
                'apartment.required' => 'Harap isi nama gedung/apartemen.',
                'zip.required' => 'Harap isi kode pos.',
                'mobile.required' => 'Harap isi nomor telepon seluler.',
                'mobile.digits_between' => 'Nomor telepon harus terdiri dari 11 sampai 13 digit',
                'mobile.numeric' => 'Nomor telepon harus berupa angka',
            ]
        );

        if ($validator->passes()) {

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

    public function showchangePasswordForm()
    {
        return view('front.account.change-password');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required' => 'Harap isi kata sandi lama.',
            'new_password.required' => 'Harap isi kata sandi baru.',
            'new_password.min' => 'Kata sandi baru minimal min 5 karakter.',
            'confirm_password.required' => 'Harap konfirmasi kata sandi baru.',
            'confirm_password.same' => 'Konfirmasi kata sandi baru tidak cocok dengan kata sandi baru yang dimasukkan.'
        ]);

        if ($validator->passes()) {

            $user = User::select('id', 'password')->where('id', Auth::user()->id)->first();

            if (!Hash::check($request->old_password, $user->password)) {

                return response()->json([
                    'status' => false,
                    'message' => 'Password lama tidak sesuai, Silahkan coba lagi.'
                ]);
            }

            User::where('id', $user->id)->update([
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

    public function forgotPassword()
    {
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        return redirect()->route('front.forgotPassword')->with('sukses', "Silahkan Cek Inbox untuk reset password.");
    }

    public function resetPassword($token)
    {

        $tokenExist = DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenExist == null) {
            return redirect()->route('front.forgotPassword')->with('error', 'invalid request');
        }

        return view('front.account.reset-password', [
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
