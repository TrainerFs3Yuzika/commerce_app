<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\support\facades\Validator;




class SettingController extends Controller
{
    public function showChangePasswordForm(){
        return view('admin.change-password');
    }

    public function processChangePassword(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
        ]);
    
        // Ambil user dari guard admin
        $user = Auth::guard('admin')->user();
    
        if (!$user) {
            return response()->json([
                'status' => false,
                'errors' => ['auth' => 'Pengguna tidak ditemukan atau tidak terotentikasi.']
            ]);
        }
    
        // Validasi ID dan ambil admin
        $id = $user->id;
        $admin = User::find($id); // Menggunakan find
    
        if ($validator->passes()) {
            // Periksa password lama
            if (!Hash::check($request->old_password, $admin->password)) {
                session()->flash('error','Password lama tidak sesuai, silahkan coba lagi.');
                return response()->json([
                    'status' => true,
                    // 'errors' => ['old_password' => 'Password lama tidak sesuai, silahkan coba lagi.']
                    // 'message' => ['old_password' => 'Password lama tidak sesuai, silahkan coba lagi.']
                ]);
            }
    
            // Update password
            $admin->password = Hash::make($request->new_password);
            $admin->save();

            session()->flash('success','Kamu berhasil mengubah password.');
    
            return response()->json([
                'status' => true,
                'message' => 'Kamu berhasil mengubah password.',
            ]);
    
        } else {            
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}
