<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Menu Pelanggan: customer mendaftar sendiri lewat website,
 * admin hanya bisa melihat dan memblokir/membuka blokir.
 */
class CustomerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:view_customer', only: ['index']),
            new Middleware('can:update_customer', only: ['block', 'unblock']),
        ];
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));

        $customers = User::customers()
            ->select('id', 'name', 'email', 'status', 'email_verified_at', 'created_at')
            ->when($search !== '', fn ($query) => $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backend.customer', compact('customers', 'search'));
    }

    // Blokir hanya mengubah status (email tidak diubah), jadi email yang diblokir tidak bisa dipakai daftar ulang.
    // Session yang sedang login ikut diputus oleh middleware EnsureUserIsActive.
    public function block(User $customer)
    {
        $customer->update(['status' => 'nonaktif']);

        return back()->with('success', "Pelanggan {$customer->name} berhasil diblokir.");
    }

    public function unblock(User $customer)
    {
        $customer->update(['status' => 'aktif']);

        return back()->with('success', "Blokir pelanggan {$customer->name} berhasil dibuka.");
    }
}
