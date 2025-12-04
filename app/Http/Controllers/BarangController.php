<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource. 
     */
    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        $barangs = $query->latest()->paginate(10);
        $kategoris = Kategori::all();

        return view('barangs.index', compact('barangs', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $kodeBarang = $this->generateKodeBarang();
        return view('barangs.create', compact('kategoris', 'kodeBarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:150',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|in:pcs,box,unit,kg',
        ], [
            'kategori_id.required' => 'Kategori wajib dipilih',
            'kategori_id. exists' => 'Kategori tidak valid',
            'kode_barang.required' => 'Kode barang wajib diisi',
            'kode_barang.unique' => 'Kode barang sudah digunakan',
            'nama_barang.required' => 'Nama barang wajib diisi',
            'stok.required' => 'Stok wajib diisi',
            'stok.min' => 'Stok tidak boleh kurang dari 0',
            'harga.required' => 'Harga wajib diisi',
            'harga.min' => 'Harga tidak boleh kurang dari 0',
            'satuan.required' => 'Satuan wajib dipilih',
        ]);

        Barang::create($validated);

        return redirect()->route('barangs.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'transaksis. user']);
        return view('barangs.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $kategoris = Kategori::all();
        return view('barangs.edit', compact('barang', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'kode_barang' => 'required|string|max:50|unique:barangs,kode_barang,' .  $barang->id,
            'nama_barang' => 'required|string|max:150',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|in:pcs,box,unit,kg',
        ]);

        $barang->update($validated);

        return redirect()->route('barangs.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();
            return redirect()->route('barangs.index')
                ->with('success', 'Barang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('barangs.index')
                ->with('error', 'Barang tidak dapat dihapus karena masih memiliki transaksi!');
        }
    }

    /**
     * Generate kode barang otomatis
     */
    private function generateKodeBarang()
    {
        $lastBarang = Barang::latest('id')->first();
        $number = $lastBarang ? (int) substr($lastBarang->kode_barang, 3) + 1 : 1;
        return 'BRG' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}