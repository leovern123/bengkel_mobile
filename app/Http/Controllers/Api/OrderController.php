<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display all orders
     */
    public function index()
    {
        $orders = Order::with([
            'customer',
            'details.service',
            'details.product'
        ])->latest()->get();

        return response()->json($orders);
    }

    /**
     * Store new transaction
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'user_id' => 'required|exists:users,id',
                'tanggal' => 'required|date',
                'details' => 'required|array'
            ]);

            // buat order utama
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'tanggal' => $request->tanggal,
                'total' => 0
            ]);

            $total = 0;

            foreach ($request->details as $item) {

                $subtotal = 0;

                // ======================
                // SERVICE
                // ======================
                if (isset($item['service'])) {

                    $subtotal =
                        $item['qty'] *
                        $item['service']['harga'];

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'service_id' => $item['service']['id'],
                        'product_id' => null,
                        'qty' => $item['qty'],
                        'subtotal' => $subtotal
                    ]);
                }

                // ======================
                // PRODUCT
                // ======================
                if (isset($item['product'])) {

                    $product = Product::findOrFail(
                        $item['product']['id']
                    );

                    // cek stok
                    if ($product->stok < $item['qty']) {

                        return response()->json([
                            'message' => 'Stok produk tidak cukup'
                        ], 400);
                    }

                    $subtotal =
                        $item['qty'] *
                        $product->harga;

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'service_id' => null,
                        'product_id' => $product->id,
                        'qty' => $item['qty'],
                        'subtotal' => $subtotal
                    ]);

                    // kurangi stok
                    $product->decrement(
                        'stok',
                        $item['qty']
                    );
                }

                $total += $subtotal;
            }

            // update total transaksi
            $order->update([
                'total' => $total
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil',
                'data' => $order->load([
                    'customer',
                    'details.service',
                    'details.product'
                ])
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'message' => 'Transaksi gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show single order
     */
    public function show(string $id)
    {
        $order = Order::with([
            'customer',
            'details.service',
            'details.product'
        ])->findOrFail($id);

        return response()->json($order);
    }

    /**
     * Delete order
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);

        $order->delete();

        return response()->json([
            'message' => 'Transaksi berhasil dihapus'
        ]);
    }

    public function invoice($id)
{
    $order = Order::with([
        'customer',
        'details.service',
        'details.product'
    ])->findOrFail($id);

    $pdf = Pdf::loadView(
        'invoice',
        compact('order')
    );

    return $pdf->download(
        'invoice-'.$order->id.'.pdf'
    );
}
}