<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $orders = Order::latest()->get();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $orders]);
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'nomor_wa'     => 'required|string|max:20',
            'email'        => 'required|email|max:255',
            'nama_produk'  => 'required|string|max:255',
            'jumlah'       => 'required|integer|min:1',
        ]);

        try {
            $order = Order::create($validated);
        } catch (\Exception $e) {
            Log::error('Gagal membuat pesanan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan. Silakan coba lagi.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'order' => $order,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'nama_pemesan' => 'sometimes|string|max:255',
            'nomor_wa'     => 'sometimes|string|max:20',
            'email'        => 'sometimes|email|max:255',
            'nama_produk'  => 'sometimes|string|max:255',
            'jumlah'       => 'sometimes|integer|min:1',
            'status'       => 'sometimes|in:baru,diproses,selesai',
        ]);

        try {
            $order->update($validated);
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui pesanan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pesanan. Silakan coba lagi.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diperbarui.',
            'data'    => $order,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dihapus.',
        ]);
    }
}
