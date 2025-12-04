@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<section class="section">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-person-circle text-primary" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-success' }} px-3 py-2">
                        <i class="bi bi-shield-{{ $user->role === 'admin' ? 'fill' : 'check' }} me-1"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                    
                    <hr class="my-4">
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>Edit User
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('users.destroy', $user) }}" 
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-trash me-1"></i>Hapus User
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informasi User</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="200">Nama Lengkap</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-success' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Terdaftar Sejak</th>
                            <td>{{ $user->created_at->format('d F Y, H:i') }} ({{ $user->created_at->diffForHumans() }})</td>
                        </tr>
                        <tr>
                            <th>Terakhir Update</th>
                            <td>{{ $user->updated_at->format('d F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Total Transaksi</th>
                            <td>
                                <span class="badge bg-info">{{ $user->transaksis->count() }} Transaksi</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($user->transaksis->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Riwayat Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Barang</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->transaksis->take(10) as $transaksi)
                                <tr>
                                    <td>{{ $transaksi->tanggal->format('d/m/Y') }}</td>
                                    <td>{{ $transaksi->barang->nama_barang }}</td>
                                    <td>
                                        <span class="badge {{ $transaksi->tipe == 'masuk' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($transaksi->tipe) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaksi->jumlah }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($user->transaksis->count() > 10)
                    <p class="text-muted text-center mb-0 mt-2">
                        <small>Menampilkan 10 transaksi terakhir dari {{ $user->transaksis->count() }} total transaksi</small>
                    </p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection