<?php

namespace App\Http\Controllers\Admin;

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

        // generate data untuk grafik bulanan
        $revenueMonthlyData = [];
        $orderMonthlyData = [];
        $revenueMonthlyLabels = [];
        $orderMonthlyLabels = [];

        for ($i = 11; $i >= 0; $i--) {
            $startOfMonth = Carbon::now()->subMonths($i)->startOfMonth()->format('Y-m-d');
            $endOfMonth = Carbon::now()->subMonths($i)->endOfMonth()->format('Y-m-d');
            $monthLabel = Carbon::now()->subMonths($i)->format('M Y');

            $revenueMonthlyLabels[] = $monthLabel;
            $orderMonthlyLabels[] = $monthLabel;

            $monthlyRevenue = Order::where('status', '!=', 'cancelled')
                ->whereDate('created_at', '>=', $startOfMonth)
                ->whereDate('created_at', '<=', $endOfMonth)
                ->sum('grand_total');
            $monthlyOrders = Order::where('status', '!=', 'cancelled')
                ->whereDate('created_at', '>=', $startOfMonth)
                ->whereDate('created_at', '<=', $endOfMonth)
                ->count();

            $revenueMonthlyData[] = $monthlyRevenue;
            $orderMonthlyData[] = $monthlyOrders;
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
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'revenueLastMonth' => $revenueLastMonth,
            'revenueLastThirtyDays' => $revenueLastThirtyDays,
            'lastMonthName' => $lastMonthName,
            'revenueMonthlyData' => $revenueMonthlyData,
            'revenueMonthlyLabels' => $revenueMonthlyLabels,
            'orderMonthlyData' => $orderMonthlyData,
            'orderMonthlyLabels' => $orderMonthlyLabels,
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}