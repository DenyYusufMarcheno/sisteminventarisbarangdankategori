<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->string('kode_barang', 50)->unique();
            $table->string('nama_barang', 150);
            $table->integer('stok')->default(0);
            $table->decimal('harga', 15, 2);
            $table->enum('satuan', ['pcs', 'box', 'unit', 'kg']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};