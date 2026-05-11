<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(
            Product::latest()->get()
        );
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nama_produk' => 'required',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // upload gambar
    if ($request->hasFile('gambar')) {

        $validated['gambar'] =
            $request->file('gambar')
                    ->store('products', 'public');
    }

    $product = Product::create($validated);

    return response()->json([
        'message' => 'Product berhasil ditambahkan',
        'data' => $product
    ], 201);
}

    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return response()->json($product);
    }

    public function update(Request $request, string $id)
{
    $product = Product::findOrFail($id);

    $validated = $request->validate([
        'nama_produk' => 'required',
        'harga' => 'required|numeric',
        'stok' => 'required|integer',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // upload gambar baru
    if ($request->hasFile('gambar')) {

        // hapus gambar lama
        if ($product->gambar) {

            Storage::disk('public')
                ->delete($product->gambar);
        }

        $validated['gambar'] =
            $request->file('gambar')
                    ->store('products', 'public');
    }

    $product->update($validated);

    return response()->json([
        'message' => 'Product berhasil diupdate',
        'data' => $product
    ]);
}

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return response()->json([
            'message' => 'Product berhasil dihapus'
        ]);
    }
}