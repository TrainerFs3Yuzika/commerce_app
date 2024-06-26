<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'asc');

        if (!empty($request->get('keyword'))) {
            $users = $users->where('name', 'like', '%' . $request->get('keyword') . '%');
            $users = $users->orWhere('email', 'like', '%' . $request->get('keyword') . '%');
        }

        $users = $users->paginate(10);

        return view('admin.users.list', [
            'users' => $users
        ]);
    }

    public function create(Request $request)
    {
        return view('admin.users.create', []);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric'
        ], [
            'name.required' => 'Harap isi nama terlebih dahulu',
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
            $user->status = $request->status;
            $user->password = Hash::make($request->password);
            $user->save();

            $message = 'Edit user berhasil!';

            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()

            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);

        if ($user == null) {
            $message = 'Users tidak ditemukan!';
            session()->flash('error', $message);
            return redirect()->route('users.index');
        }

        return view('admin.users.edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if ($user == null) {
            $message = 'Users tidak ditemukan!';
            session()->flash('error', $message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'phone' => 'required|numeric'
        ], [
            'name.required' => 'Harap isi nama terlebih dahulu',
            'email.required' => 'Harap isi email terlebih dahulu',
            'email.email' => 'format alamat email tidak valid.',
            'email.unique' => 'Email sudah ada, harap gunakan email yang berbeda',
            'phone.required' => 'Harap isi nomor telepon terlebih dahulu',
            'phone.numeric' => 'Nomor telepon harus berupa angka',
        ]);

        if ($validator->passes()) {

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->status = $request->status;

            if ($request->password != '') {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $message = 'Edit user berhasil!';

            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()

            ]);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user == null) {
            $message = 'Users tidak ditemukan!';
            session()->flash('error', $message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        $user->delete();

        $message = 'Users berhasil dihapus!';
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
