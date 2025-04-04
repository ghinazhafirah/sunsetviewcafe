<div>
    <div wire:poll.1s> <!-- Update otomatis setiap 5 detik -->

        <!-- Perhitungan Total Pemasukan -->
        @php
            $totalPemasukan = $orders->sum('total_price');
        @endphp

        <!-- Penjualan & Pendapatan Start -->
        <div class="container-fluid pt-4 px-4">
            <div class="row justify-content-center g-4">
                <div class="col-lg-4 mt-8">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                        <i class="fa fa-chart-line fa-3x text-primary"></i>
                        <div class="ms-3">
                            <p class="mb-2">Pembayaran Terakhir</p>
                            <h6 class="mb-0">Rp. 38.000,00</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-8">
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                        <i class="fa fa-chart-bar fa-3x text-primary"></i>
                        <div class="ms-3">
                            <p class="mb-2">Total Saldo Terakhir</p>
                            <h6 class="mb-0">Rp. 38.000,00</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Penjualan & Pendapatan End -->

        <!-- Tabel Penjualan Start -->
        {{-- Notifikasi --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show text-center mt-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="container bg-light rounded mt-4 p-2">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="mb-3">DATA TRANSAKSI</h5>
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
                                        {{-- @if ($transaction->status == 'pending')
                                                <form action="{{ route('dashboard.confirmPayment', $transaction->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Konfirmasi Cash</button>
                                                </form> --}}

                                        @if ($transaction->payment_method == 'cash' && $transaction->status == 'pending')
                                            <form action="{{ route('dashboard.confirmPayment', $transaction->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Konfirmasi Cash</button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Sudah Dibayar</button>
                                        @endif
                                    </td> <!-- Action -->
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
    <!-- Pagination -->
    <div class="mt-3">
        {{ $orders->links() }}
    </div>
</div>
</div>
</div>
