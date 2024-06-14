<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index()
    {
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 1)->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');

        // pendapatan bulan ini
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');
        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('grand_total');

        // pendapatan bulan lalu
        $lastMonthStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->format('M');
        $revenueLastMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastMonthStartDate)
            ->whereDate('created_at', '<=', $lastMonthEndDate)
            ->sum('grand_total');

        // penjualan 30 hari terakhir
        $LastThirtyDayStartDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $revenueLastThirtyDays = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $LastThirtyDayStartDate)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('grand_total');

        // generate data untuk grafik
        $revenueData = [];
        $orderData = [];
        $revenueLabels = [];
        $orderLabels = [];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(29 - $i)->format('Y-m-d');
            $revenueLabels[] = $date;
            $orderLabels[] = $date;

            $dailyRevenue = Order::where('status', '!=', 'cancelled')
                ->whereDate('created_at', $date)
                ->sum('grand_total');
            $dailyOrders = Order::where('status', '!=', 'cancelled')
                ->whereDate('created_at', $date)
                ->count();

            $revenueData[] = $dailyRevenue;
            $orderData[] = $dailyOrders;
        }

        // hapus gambar sementara
        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
        $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get();

        foreach ($tempImages as $tempImage) {
            $path = public_path('/temp/' . $tempImage->name);
            $thumbPath = public_path('/temp/thumb/' . $tempImage->name);

            // hapus gambar utama
            if (File::exists($path)) {
                File::delete($path);
            }

            // hapus gambar thumbnail
            if (File::exists($thumbPath)) {
                File::delete($thumbPath);
            }

            TempImage::where('id', $tempImage->id)->delete();
        }

        return view('admin.dashboard', [
            'totalOrders' => number_format($totalOrders, 0, ',', '.'),
            'totalProducts' => number_format($totalProducts, 0, ',', '.'),
            'totalCustomers' => number_format($totalCustomers, 0, ',', '.'),
            'totalRevenue' => number_format($totalRevenue, 0, ',', '.'),
            'revenueThisMonth' => number_format($revenueThisMonth, 0, ',', '.'),
            'revenueLastMonth' => number_format($revenueLastMonth, 0, ',', '.'),
            'revenueLastThirtyDays' => number_format($revenueLastThirtyDays, 0, ',', '.'),
            'lastMonthName' => $lastMonthName,
            'revenueData' => array_map(function($value) { return number_format($value, 0, ',', '.'); }, $revenueData),
            'revenueLabels' => $revenueLabels,
            'orderData' => $orderData,
            'orderLabels' => $orderLabels,
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}