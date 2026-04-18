<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\ZonaWilayah;
use App\Http\Requests\Admin\StorePelangganRequest;
use App\Http\Requests\Admin\UpdatePelangganRequest;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['zona_id', 'is_active', 'search']);
        $pelanggan = Pelanggan::filter($filters)->with(['zona', 'user'])->paginate(15);
        $zonas = ZonaWilayah::where('is_active', true)->get();

        return view('admin.pelanggan.index', compact('pelanggan', 'zonas', 'filters'));
    }

    public function create()
    {
        $zonas = ZonaWilayah::where('is_active', true)->get();
        return view('admin.pelanggan.create', compact('zonas'));
    }

    public function store(StorePelangganRequest $request)
    {
        Pelanggan::create($request->validated());

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pelanggan = Pelanggan::with(['zona', 'user', 'pengaduan'])->findOrFail($id);
        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $zonas = ZonaWilayah::where('is_active', true)->get();
        return view('admin.pelanggan.edit', compact('pelanggan', 'zonas'));
    }

    public function update(UpdatePelangganRequest $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        $data = $request->validated();
        if (!$request->has('is_active')) {
            $data['is_active'] = false;
        }

        $pelanggan->update($data);

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $aktif = $pelanggan->pengaduan()
            ->whereNotIn('status', ['selesai', 'ditolak'])
            ->count();

        if ($aktif > 0) {
            return redirect()->back()
                ->with('error', "Pelanggan tidak dapat dihapus karena masih memiliki {$aktif} pengaduan aktif.");
        }

        $pelanggan->delete();
        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Data pelanggan dihapus.');
    }
}
