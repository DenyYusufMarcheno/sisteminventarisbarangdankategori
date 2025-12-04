<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKategori = Kategori::count();
        $totalBarang = Barang::count();
        $totalTransaksi = Transaksi::count();
        $totalUser = User::count();

        // Barang dengan stok rendah (< 10)
        $barangStokRendah = Barang::with('kategori')
            ->where('stok', '<', 10)
            ->orderBy('stok', 'asc')
            ->limit(5)
            ->get();

        // Transaksi terbaru
        $transaksiTerbaru = Transaksi::with(['barang', 'user'])
            ->latest('tanggal')
            ->limit(5)
            ->get();

        // Statistik transaksi per bulan (6 bulan terakhir)
        $transaksiPerBulan = Transaksi::select(
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('SUM(CASE WHEN tipe = "masuk" THEN jumlah ELSE 0 END) as total_masuk'),
                DB::raw('SUM(CASE WHEN tipe = "keluar" THEN jumlah ELSE 0 END) as total_keluar')
            )
            ->where('tanggal', '>=', now()->subMonths(6))
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('dashboard', compact(
            'totalKategori',
            'totalBarang',
            'totalTransaksi',
            'totalUser',
            'barangStokRendah',
            'transaksiTerbaru',
            'transaksiPerBulan'
        ));
    }
}