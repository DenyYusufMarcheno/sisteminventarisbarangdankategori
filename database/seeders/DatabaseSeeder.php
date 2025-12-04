<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@inventaris.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Staff User
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@inventaris.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // Create Kategoris
        $kategoris = [
            ['nama_kategori' => 'Elektronik', 'deskripsi' => 'Barang-barang elektronik'],
            ['nama_kategori' => 'Furniture', 'deskripsi' => 'Peralatan kantor dan furniture'],
            ['nama_kategori' => 'Alat Tulis', 'deskripsi' => 'Keperluan tulis menulis'],
            ['nama_kategori' => 'Komputer', 'deskripsi' => 'Perangkat komputer dan aksesoris'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }

        // Create Barangs
        $barangs = [
            [
                'kategori_id' => 1,
                'kode_barang' => 'BRG00001',
                'nama_barang' => 'Laptop Dell Latitude',
                'stok' => 15,
                'harga' => 8500000,
                'satuan' => 'unit',
            ],
            [
                'kategori_id' => 1,
                'kode_barang' => 'BRG00002',
                'nama_barang' => 'Mouse Wireless',
                'stok' => 50,
                'harga' => 150000,
                'satuan' => 'pcs',
            ],
            [
                'kategori_id' => 2,
                'kode_barang' => 'BRG00003',
                'nama_barang' => 'Meja Kantor',
                'stok' => 8,
                'harga' => 1200000,
                'satuan' => 'unit',
            ],
            [
                'kategori_id' => 3,
                'kode_barang' => 'BRG00004',
                'nama_barang' => 'Pulpen Pilot',
                'stok' => 5,
                'harga' => 5000,
                'satuan' => 'pcs',
            ],
            [
                'kategori_id' => 4,
                'kode_barang' => 'BRG00005',
                'nama_barang' => 'Keyboard Mechanical',
                'stok' => 20,
                'harga' => 750000,
                'satuan' => 'pcs',
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}