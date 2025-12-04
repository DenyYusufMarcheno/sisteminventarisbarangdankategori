<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource. 
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['barang. kategori', 'user']);

        // Filter berdasarkan tipe
        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $transaksis = $query->latest('tanggal')->paginate(10);

        return view('transaksis. index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barangs = Barang::with('kategori')->get();
        return view('transaksis.create', compact('barangs'));
    }

    /**
     * Store a newly created resource in storage. 
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ], [
            'barang_id.required' => 'Barang wajib dipilih',
            'tipe.required' => 'Tipe transaksi wajib dipilih',
            'jumlah.required' => 'Jumlah wajib diisi',
            'jumlah.min' => 'Jumlah minimal 1',
            'tanggal.required' => 'Tanggal wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($validated['barang_id']);

            // Validasi stok untuk transaksi keluar
            if ($validated['tipe'] === 'keluar') {
                if ($barang->stok < $validated['jumlah']) {
                    return back()->withInput()
                        ->with('error', 'Stok tidak mencukupi!  Stok tersedia: ' . $barang->stok);
                }
                $barang->decrement('stok', $validated['jumlah']);
            } else {
                $barang->increment('stok', $validated['jumlah']);
            }

            // Simpan transaksi
            Transaksi::create([
                'barang_id' => $validated['barang_id'],
                'user_id' => auth()->id(),
                'tipe' => $validated['tipe'],
                'jumlah' => $validated['jumlah'],
                'tanggal' => $validated['tanggal'],
                'keterangan' => $validated['keterangan'],
            ]);

            DB::commit();

            return redirect()->route('transaksis.index')
                ->with('success', 'Transaksi berhasil ditambahkan! ');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource. 
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['barang.kategori', 'user']);
        return view('transaksis.show', compact('transaksi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaksi $transaksi)
    {
        $barangs = Barang::with('kategori')->get();
        return view('transaksis.edit', compact('transaksi', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($validated['barang_id']);
            $oldBarang = Barang::findOrFail($transaksi->barang_id);

            // Kembalikan stok lama
            if ($transaksi->tipe === 'masuk') {
                $oldBarang->decrement('stok', $transaksi->jumlah);
            } else {
                $oldBarang->increment('stok', $transaksi->jumlah);
            }

            // Update stok baru
            if ($validated['tipe'] === 'keluar') {
                if ($barang->stok < $validated['jumlah']) {
                    DB::rollBack();
                    return back()->withInput()
                        ->with('error', 'Stok tidak mencukupi!  Stok tersedia: ' .  $barang->stok);
                }
                $barang->decrement('stok', $validated['jumlah']);
            } else {
                $barang->increment('stok', $validated['jumlah']);
            }

            // Update transaksi
            $transaksi->update($validated);

            DB::commit();

            return redirect()->route('transaksis.index')
                ->with('success', 'Transaksi berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' .  $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage. 
     */
    public function destroy(Transaksi $transaksi)
    {
        DB::beginTransaction();
        try {
            $barang = $transaksi->barang;

            // Kembalikan stok
            if ($transaksi->tipe === 'masuk') {
                $barang->decrement('stok', $transaksi->jumlah);
            } else {
                $barang->increment('stok', $transaksi->jumlah);
            }

            $transaksi->delete();

            DB::commit();

            return redirect()->route('transaksis.index')
                ->with('success', 'Transaksi berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}