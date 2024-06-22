<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Carbon;

use Khill\Lavacharts\Lavacharts;
use Mpdf\Mpdf;

class GeneratePDFController extends Controller
{


    public function downloadOrder($id)
    {
        $order = Order::findOrFail($id);
        $orderItems = OrderItem::where('order_id', $id)->get();
        $orderItemsCount = $orderItems->count();

        $data = [
            'order' => $order,
            'orderItems' => $orderItems,
            'orderItemsCount' => $orderItemsCount,
        ];

        $mpdf = new \Mpdf\Mpdf(); // Sesuaikan dengan library PDF generator yang Anda gunakan

        // Render view into HTML
        $html = view('front.account.order-detail-pdf', $data)->render();

        // Load HTML content into PDF
        $mpdf->WriteHTML($html);

        // Output the PDF as a downloadable file
        $mpdf->Output('order_' . $order->id . '.pdf', 'D'); // 'D' untuk download

        // Redirect back to the order detail page after download
        return redirect()->back()->with('success', 'Bukti Order Customer Berhasil Di Download');
    }

    public function generateMonthlySalesReport($month = null)
    {
        // Tentukan bulan berdasarkan parameter atau defaultnya
        if ($month === null) {
            $month = Carbon::now()->month;
        }

        // Jika ingin bulan kemarin
        if ($month == 'last') {
            $month = Carbon::now()->subMonth()->month;
        }

        // Ambil data order berdasarkan bulan yang dipilih
        $orders = Order::whereMonth('created_at', '=', $month)->get();

        // Cek apakah ada order pada bulan yang dipilih
        if ($orders->isEmpty()) {
            return redirect()->route('orders.index')->with('error', 'Tidak ada data order pada bulan yang dipilih.');
        }

        // Ambil data pendapatan bulanan dari order
        $monthlyRevenue = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(grand_total) as total')
            ->whereMonth('created_at', '=', $month)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Menghitung total keseluruhan pendapatan
        $totalPendapatan = $monthlyRevenue->sum('total');

        // Data untuk grafik pendapatan bulanan
        $revenueMonthlyLabels = [];
        $revenueMonthlyData = [];

        foreach ($monthlyRevenue as $revenue) {
            $revenueMonthlyLabels[] = Carbon::parse($revenue->month)->format('M Y');
            $revenueMonthlyData[] = $revenue->total;
        }

        // Ambil data jumlah pesanan bulanan dari order
        $monthlyOrders = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(id) as total')
            ->whereMonth('created_at', '=', $month)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Data untuk grafik jumlah pesanan bulanan
        $orderMonthlyLabels = [];
        $orderMonthlyData = [];

        foreach ($monthlyOrders as $order) {
            $orderMonthlyLabels[] = Carbon::parse($order->month)->format('M Y');
            $orderMonthlyData[] = $order->total;
        }

        // Buat instance Lavacharts baru
        $lava = new Lavacharts();

        // Grafik untuk pendapatan
        $revenueChart = $lava->DataTable();
        $revenueChart->addDateColumn('Bulan')
            ->addNumberColumn('Pendapatan');

        foreach ($monthlyRevenue as $revenue) {
            $revenueChart->addRow([$revenue->month, $revenue->total]);
        }

        $lava->LineChart('revenueChart', $revenueChart);

        // Grafik untuk jumlah pesanan
        $ordersChart = $lava->DataTable();
        $ordersChart->addDateColumn('Bulan')
            ->addNumberColumn('Pesanan');

        foreach ($monthlyOrders as $order) {
            $ordersChart->addRow([$order->month, $order->total]);
        }

        $lava->BarChart('ordersChart', $ordersChart);

        // Render view into HTML
        $chartsHtml = $lava->render('LineChart', 'revenueChart', 'revenueChart');
        $chartsHtml .= $lava->render('BarChart', 'ordersChart', 'ordersChart');

        $html = view('admin.orders.laporan-penjualan-bulanan', compact(
            'orders',
            'month',
            'revenueMonthlyLabels',
            'revenueMonthlyData',
            'orderMonthlyLabels',
            'orderMonthlyData',
            'chartsHtml',
            'totalPendapatan' // Mengirimkan total pendapatan ke view
        ))->render();

        // Load HTML content into PDF
        $pdf = new Mpdf();
        $pdf->WriteHTML($html);

        // Download PDF dengan nama file berdasarkan bulan
        return $pdf->Output('laporan_penjualan_bulanan_' . Carbon::create()->month($month)->isoFormat('MMMM') . '.pdf', 'D');
    }
}