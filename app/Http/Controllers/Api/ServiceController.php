<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json(
            Service::latest()->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_service' => 'required',
            'harga' => 'required|numeric'
        ]);

        $service = Service::create($validated);

        return response()->json([
            'message' => 'Service berhasil ditambahkan',
            'data' => $service
        ], 201);
    }

    public function show(string $id)
    {
        $service = Service::findOrFail($id);

        return response()->json($service);
    }

    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'nama_service' => 'required',
            'harga' => 'required|numeric'
        ]);

        $service->update($validated);

        return response()->json([
            'message' => 'Service berhasil diupdate',
            'data' => $service
        ]);
    }

    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);

        $service->delete();

        return response()->json([
            'message' => 'Service berhasil dihapus'
        ]);
    }
}