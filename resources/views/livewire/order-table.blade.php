<div>
    <div wire:poll.1s> <!-- Update otomatis setiap 5 detik -->

        {{-- Notifikasi --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show text-center mt-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Input Search -->
        <div class="row mb-3">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="position-relative">
                    <!-- Icon Search for mobile only -->
                    <input type="text" class="form-control ps-5 search-input" wire:model.debounce.500ms="search"
                        id="searchInput" placeholder="Cari nama, WA, metode, atau tanggal...">
                    <i
                        class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted search-icon"></i>
                </div>
            </div>


            <div class="container bg-light rounded mt-4 p-2">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="mb-3">DATA TRANSAKSI</h4>
                    <a href="{{ route('dashboard.export.orders') }}" class="btn btn-success">
                        Export Excel
                    </a>
                </div>

                <div class="table-responsive w-100">
                    <div class="row g-2 align-items-center mb-3">
                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                            <thead>
                                <tr class="text-dark text-center">
                                    {{-- <th scope="col"><input class="form-check-input" type="checkbox"></th> --}}
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
                                @foreach ($orders as $transaction)
                                    <tr class="text-center">
                                        <td>{{ $loop->iteration }}</td><!-- Nomor urut -->
                                        <td>{{ $transaction->created_at->format('d M Y H:i') }}</td> <!-- Tanggal -->
                                        <td>{{ $transaction->customer_name }}</td> <!-- Nama Pelanggan -->
                                        <td>{{ $transaction->customer_whatsapp }}</td> <!-- Nomor WhatsApp -->
                                        <td>Rp. {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                        <!-- Total Harga -->
                                        <td>
                                            @if ($transaction->payment_method)
                                                {{ ucfirst($transaction->payment_method) }}
                                            @else
                                                <span class="badge bg-secondary">Belum Dipilih</span>
                                            @endif
                                        </td> <!-- Metode Pembayaran -->
                                        <td>
                                            @if ($transaction->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($transaction->status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-danger">Failed</span>
                                            @endif
                                        </td> <!-- Status Pembayaran -->

                                        <td>
                                            @if ($transaction->payment_method == 'cash' && $transaction->status == 'pending')
                                                <a href="{{ route('dashboard.show', $transaction->uuid) }}"
                                                    class="btn badge bg-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('dashboard.show', $transaction->uuid) }}"
                                                    class="btn badge bg-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endif
                                        </td>



                                        {{-- <td>   
                                            @if ($transaction->payment_method == 'cash' && $transaction->status == 'pending')
                                                <form action="{{ route('dashboard.confirmPayment', $transaction->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Konfirmasi Cash</button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>Sudah Dibayar</button>
                                            @endif
                                        </td> <!-- Action --> --}}
                                        <td>
                                            <form action="/dashboard/{{ $transaction->id }}" method="post"
                                                class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <button class="btn badge bg-danger"
                                                    onclick="return confirm('Yakin ingin menghapus transaksi ini?')"><i
                                                        class="bi bi-x-square"></i></button>
                                            </form>
                                            {{-- <form action="/dashboard/{{ $transaction->id }}" method="post" class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <button class="btn badge bg-danger" onclick="return confirm('Yakin ingin menghapus transaksi ini?')"><i class="bi bi-x-square"></i></button>
                                            </form>  --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- <!-- Pagination -->
        <div class="pagination mt-3 d-flex justify-content-center">
            {{ $orders->links('vendor.pagination.index') }}
        </div> --}}
    </div>
</div>
