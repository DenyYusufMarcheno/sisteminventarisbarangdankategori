<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter berdasarkan periode
        $tanggalDari = $request->input('tanggal_dari', now()->startOfMonth()->format('Y-m-d'));
        $tanggalSampai = $request->input('tanggal_sampai', now()->format('Y-m-d'));

        // Laporan Stok Barang
        $laporanStok = Barang::with('kategori')
            ->select('barangs.*')
            ->orderBy('stok', 'asc')
            ->get();

        // Laporan Transaksi Masuk
        $transaksiMasuk = Transaksi::with(['barang. kategori', 'user'])
            ->where('tipe', 'masuk')
            ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->latest('tanggal')
            ->get();

        $totalMasuk = $transaksiMasuk->sum('jumlah');

        // Laporan Transaksi Keluar
        $transaksiKeluar = Transaksi::with(['barang. kategori', 'user'])
            ->where('tipe', 'keluar')
            ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->latest('tanggal')
            ->get();

        $totalKeluar = $transaksiKeluar->sum('jumlah');

        // Laporan per Kategori
        $laporanKategori = Kategori::withCount('barangs')
            ->withSum('barangs', 'stok')
            ->get();

        // Barang paling sering transaksi
        $barangTerpopuler = Transaksi::with('barang')
            ->select('barang_id', DB::raw('COUNT(*) as total_transaksi'), DB::raw('SUM(jumlah) as total_jumlah'))
            ->whereBetween('tanggal', [$tanggalDari, $tanggalSampai])
            ->groupBy('barang_id')
            ->orderByDesc('total_transaksi')
            ->limit(10)
            ->get();

        return view('laporan.index', compact(
            'laporanStok',
            'transaksiMasuk',
            'transaksiKeluar',
            'totalMasuk',
            'totalKeluar',
            'laporanKategori',
            'barangTerpopuler',
            'tanggalDari',
            'tanggalSampai'
        ));
    }

    /**
     * Export laporan ke Excel/PDF (opsional)
     */
    public function export(Request $request)
    {
        // Implementasi export jika diperlukan
        // Bisa menggunakan package seperti maatwebsite/excel atau barryvdh/laravel-dompdf
    }
}