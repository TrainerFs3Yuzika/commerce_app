<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderEmail;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::orderBy('id', 'asc')
            ->select('orders.*', 'users.name', 'users.email')
            ->leftJoin('users', 'users.id', 'orders.user_id')
            ->orderBy('orders.created_at');

        if ($request->get('keyword') != "") {
            $orders = $orders->where('users.name', 'like', '%' . $request->keyword . '%')
                ->orWhere('users.email', 'like', '%' . $request->keyword . '%')
                ->orWhere('orders.id', 'like', '%' . $request->keyword . '%');
        }

        $orders = $orders->paginate(10);

        return view('admin.orders.list', [
            'orders' => $orders
        ]);
    }

    public function detail($orderId)
    {
        $order = Order::select('orders.*', 'countries.name as countryName')
            ->where('orders.id', $orderId)
            ->leftJoin('countries', 'countries.id', 'orders.country_id')
            ->first();

        $orderItems = OrderItem::where('order_id', $orderId)->get();

        return view('admin.orders.detail', [
            'order' => $order,
            'orderItems' => $orderItems
        ]);
    }

    public function changeOrderStatus(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();

        $message = 'Update status order berhasil';

        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function sendInvoiceEmail(Request $request, $orderId)
{
    $order = Order::with('user', 'items')->find($orderId); // Pastikan relasi 'user' dan 'items' dimuat
    if (!$order) {
        $message = 'Order tidak ditemukan';
        return response()->json([
            'status' => false,
            'message' => $message
        ], 404);
    }

    $customerEmail = $order->user->email ?? null;
    if (!$customerEmail) {
        $message = 'Email pelanggan tidak ditemukan';
        return response()->json([
            'status' => false,
            'message' => $message
        ], 404);
    }

    $adminEmail = 'kuyyybelanjaaa@gmail.com';

    $mailData = [
        'subject' => 'Invoice Order #' . $orderId,
        'userType' => $request->userType, // Pastikan userType disertakan
        'order' => $order, // Sertakan order dalam mailData
    ];

    if ($request->userType == 'customer') {
        // Kirim email hanya ke customer
        Mail::to($customerEmail)
            ->send(new OrderEmail($mailData));
    } else {
        // Kirim email hanya ke admin
        Mail::to($adminEmail)
            ->send(new OrderEmail($mailData));
    }

    $message = 'Email order berhasil dikirim';

    session()->flash('success', $message);

    return response()->json([
        'status' => true,
        'message' => $message
    ]);
}


}
