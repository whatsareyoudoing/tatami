<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Dashboard admin: ringkasan + daftar semua transaksi (status diubah lewat popup).
 */
class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Editor tidak punya akses pesanan: dashboard tampil tanpa data transaksi
        if (!$request->user()->can('view_order')) {
            return view('backend.dashboard', ['orders' => null]);
        }

        $orders = Order::with(['user:id,name,email', 'items.variant'])
            ->latest()
            ->paginate(10);

        return view('backend.dashboard', [
            'orders'       => $orders,
            'revenue'      => Order::whereIn('status', Order::PAID)->sum('total'),
            'newOrders'    => Order::where('status', Order::BARU)->count(),
            'activeCustomers' => User::customers()->where('status', 'aktif')->count(),
        ]);
    }
}
