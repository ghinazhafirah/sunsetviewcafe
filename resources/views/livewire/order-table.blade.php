<div>
    {{-- Ini adalah div Livewire yang membungkus seluruh konten --}}
    <div wire:poll.100ms> {{-- Notifikasi --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            @if (session()->has('success'))
                {{-- Tambah w-100 agar notifikasi mengambil lebar penuh --}}
                <div class="alert alert-success alert-dismissible fade show text-center mt-4 w-100" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="row mb-3">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="position-relative">
                    <input type="text" class="form-control ps-5 search-input" wire:model.debounce.500ms="search"
                        id="searchInput" placeholder="Cari nama, WA, metode, atau tanggal...">
                    <i
                        class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted search-icon"></i>
                </div>
            </div>
            {{-- Pertimbangkan menambahkan div kosong di sini jika Anda ingin search input hanya di kiri --}}
            {{-- dan tidak ingin elemen lain berada di kanan pada desktop. --}}
            {{-- <div class="col-12 col-md-6 col-lg-8"></div> --}}
        </div>

        {{-- Mengubah kelas container di sini --}}
        {{-- Menggunakan `p-3` untuk padding yang lebih baik dan konsisten --}}
        <div class="container bg-light rounded mt-4 p-3">
            <div class="d-flex align-items-center justify-content-between mb-4">
                {{-- Mengubah mb-3 menjadi mb-0 karena sudah ada mb-4 di parent --}}
                <h4 class="mb-0">DATA TRANSAKSI</h4>
                <a href="{{ route('dashboard.export.orders') }}" class="btn btn-success">
                    Export Excel
                </a>
            </div>

            {{-- **Perbaikan Utama di Sini:** --}}
            {{-- table-responsive harus langsung membungkus elemen table --}}
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark text-center">
                            <th scope="col">No.</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Nomor WA</th>
                            <th scope="col">Total</th>
                            <th scope="col">Metode Pembayaran</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                            <th scope="col">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $transaction)
                            {{-- Gunakan @forelse untuk menangani data kosong --}}
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $transaction->customer_name }}</td>
                                <td>{{ $transaction->customer_whatsapp }}</td>
                                <td>Rp. {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                <td>
                                    @if ($transaction->payment_method)
                                        {{ ucfirst($transaction->payment_method) }}
                                    @else
                                        <span class="badge bg-secondary">Belum Dipilih</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif ($transaction->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Tidak perlu logic @if di sini jika kedua kondisi mengarah ke link yang sama --}}
                                    <a href="{{ route('dashboard.show', $transaction->uuid) }}"
                                        class="btn badge bg-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                                <td>
                                    <form action="/dashboard/{{ $transaction->id }}" method="post" class="d-inline">
                                        @method('delete')
                                        @csrf
                                        <button class="btn badge bg-danger"
                                            onclick="return confirm('Yakin ingin menghapus transaksi ini?')"><i
                                                class="bi bi-x-square"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data transaksi ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> {{-- Penutup table-responsive --}}
        </div> {{-- Penutup container bg-light rounded --}}
    </div> {{-- Penutup div wire:poll.1s --}}
</div>
