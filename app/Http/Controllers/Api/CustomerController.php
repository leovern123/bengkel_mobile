<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json(
            Customer::latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'no_plat' => 'required',
            'no_hp' => 'nullable'
        ]);

        $customer = Customer::create($validated);

        return response()->json([
            'message' => 'Customer berhasil ditambahkan',
            'data' => $customer
        ], 201);
    }

    public function show(string $id)
    {
        $customer = Customer::findOrFail($id);

        return response()->json($customer);
    }

    public function update(Request $request, string $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required',
            'no_plat' => 'required',
            'no_hp' => 'nullable'
        ]);

        $customer->update($validated);

        return response()->json([
            'message' => 'Customer berhasil diupdate',
            'data' => $customer
        ]);
    }

    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return response()->json([
            'message' => 'Customer berhasil dihapus'
        ]);
    }
}