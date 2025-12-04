@extends('layouts. app')

@section('title', 'Daftar Kategori')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Kategori</h5>
            <a href="{{ route('kategoris.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Kategori
            </a>
        </div>
        <div class="card-body">
            @if($kategoris->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Barang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kategoris as $index => $kategori)
                            <tr>
                                <td>{{ $kategoris->firstItem() + $index }}</td>
                                <td class="font-semibold">{{ $kategori->nama_kategori }}</td>
                                <td>{{ Str::limit($kategori->deskripsi, 50) ??  '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $kategori->barangs_count }} Barang</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('kategoris.show', $kategori) }}" 
                                           class="btn btn-sm btn-info" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('kategoris.edit', $kategori) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('kategoris.destroy', $kategori) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $kategoris->links() }}
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>Belum ada data kategori
                </div>
            @endif
        </div>
    </div>
</section>
@endsection