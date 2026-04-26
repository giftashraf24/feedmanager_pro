<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders   = Order::count();
        $totalSales    = Order::sum('total_price');
        $totalProducts = Product::count();
        $lowStock      = Product::where('stock_quantity', '>', 0)
                                ->where('stock_quantity', '<', 5)
                                ->get();
        $outOfStock    = Product::where('stock_quantity', 0)->count();

        // Sales per day (last 7 days)
        $salesData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 5 selling products
        $topProducts = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(quantity * price) as revenue')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        // Category breakdown
        $categoryData = Product::select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();

        return view('dashboard.index', compact(
            'totalOrders',
            'totalSales',
            'totalProducts',
            'lowStock',
            'outOfStock',
            'salesData',
            'topProducts',
            'recentOrders',
            'categoryData'
        ));
    }
}
