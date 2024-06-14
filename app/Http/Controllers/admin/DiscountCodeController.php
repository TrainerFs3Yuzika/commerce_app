<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {

        $discountCoupons = DiscountCoupon::orderBy('id', 'asc');

        if (!empty($request->get('keyword'))) {
            $discountCoupons = $discountCoupons->where('name', 'like', '%' . $request->get('keyword') . '%');
            $discountCoupons = $discountCoupons->orWhere('code', 'like', '%' . $request->get('keyword') . '%');
        }
        $discountCoupons = $discountCoupons->paginate(10);

        return view('admin.coupon.list', compact('discountCoupons'));
    }

    public function create()
    {
        return view('admin.coupon.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ], [
            'code.required' => 'Harap diisi terlebih dahulu.',
            'type.required' => 'Harap diisi terlebih dahulu.',
            'discount_amount.required' => 'Harap diisi terlebih dahulu.',
            'discount_amount.numeric' => 'Harap isi dengan angka.',
            'status.required' => 'Harap diisi terlebih dahulu.',
        ]);

        if ($validator->passes()) {

            // tanggal mulai harus tergenerate dengan tanggal sekarang
            if (!empty($request->starts_at)) {
                $now = Carbon::now();
                $startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($startsAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => 'Tanggal Mulai Tidak boleh sebelum hari ini!']
                    ]);
                }
            }

            // tanggal mulai harus melebihi tanggal mulai
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($expiresAt->gt($startsAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Tanggal Selesai harus Lebih Besar dari Tanggal Mulai!']
                    ]);
                }
            }


            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $message = 'Kupon Diskon Berhasil Ditambahkan';

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

        $coupon = DiscountCoupon::find($id);

        if ($coupon == null) {
            session()->flash('error', 'Data Tidak Ditemukan');
            return redirect()->route('coupons.index');
        }

        $data['coupon'] = $coupon;

        return view('admin.coupon.edit', $data);
    }

    public function update(Request $request, $id)
    {

        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null) {
            session()->flash('error', 'Data tidak ditemukan');
            return response()->json([
                'status' => true
            ]);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            // tanggal mulai harus melebihi tanggal mulai
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startsAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);

                if ($expiresAt->gt($startsAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Tanggal Selesai harus Lebih Besar dari Tanggal Mulai!']
                    ]);
                }
            }

            // Perbarui nilai-nilai properti objek $discountCode
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();

            $message = 'Kupon Diskon Berhasil Diupdate';

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


    public function destroy(Request $request, $id)
    {

        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null) {
            session()->flash('error', 'Data tidak ditemukan');
            return response()->json([
                'status' => true
            ]);
        }

        $discountCode->delete();
        session()->flash('success', 'Kupon Diskon Berhasil Dihapus');
        return response()->json([
            'status' => true
        ]);
    }
}
