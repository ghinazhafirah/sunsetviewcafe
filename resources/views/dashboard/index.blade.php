@extends('dashboard.layouts.main') {{-- mengambil menggunakan layout main.blade --}}

@section('container')
    <!-- apapun yang ada didalam section akan menggantikan yield -->
    <div class="container-fluid pt-4 px-4">
        <div class="row justify-content-center g-4">
            {{-- <div class="col-lg-4 mt-8">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-money-bill-wave fa-3x text-success"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Pembayaran Cash</p>
                        <h6 class="mb-0">Rp. {{ number_format($totalCash, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-lg-4 mt-8">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-credit-card fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Pembayaran Midtrans</p>
                        <h6 class="mb-0">Rp. {{ number_format($totalMidtrans, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-lg-4 mt-8">
                <button
                    class="bg-light hover-warning rounded d-flex align-items-center justify-content-between p-4 border-0 w-100 shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#grafikPemasukanModal">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3 text-start text-dark">
                        <p class="mb-2">Lihat Grafik Pemasukan</p>
                    </div>
                </button>
            </div> --}}

            <div class="col-lg-4 mt-8">
                <button
                    class="rounded d-flex align-items-center justify-content-between p-4 border-0 w-100 shadow-sm text-dark"
                    data-bs-toggle="modal" data-bs-target="#grafikPemasukanModal"
                    style="background-color: #f8f9fa; transition: background-color 0.3s ease;"
                    onmouseenter="this.style.backgroundColor='#ffc107'" onmouseleave="this.style.backgroundColor='#f8f9fa'">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3 text-start">
                        <p class="mb-2">Lihat Grafik Pemasukan</p>
                    </div>
                </button>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="grafikPemasukanModal" tabindex="-1" aria-labelledby="chartIncomeLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="chartIncomeLabel">Grafik Pemasukan Cash | Midtrans</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="col-lg-12 p-2">
                            <form method="GET" action="{{ route('dashboard.index') }}" class="row g-2 mb-2">
                                <input type="hidden" name="showModal" value="1">
                                <div class="col-md-6">
                                    <label for="month" class="form-label">Bulan:</label>
                                    <select name="month" id="month" class="form-select">
                                        @foreach (range(1, 12) as $m)
                                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                                {{ $selectedMonth == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="year" class="form-label">Tahun:</label>
                                    <select name="year" id="year" class="form-select">
                                        @foreach (range(now()->year, now()->year + 5) as $y)
                                            <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                                {{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 text-end mt-2">
                                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                </div>
                            </form>
                        </div>

                        <div class="modal-body p-1">
                            <canvas id="salse-revenue-modal" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-8">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-credit-card fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Hari Ini</p>
                        <h6 class="mb-0">Rp. {{ number_format($totalHariIni, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-8">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                    <i class="fa fa-chart-line fa-3x text-warning"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Pemasukan</p>
                        <h6 class="mb-0">Rp. {{ number_format($totalPemasukan, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Gunakan komponen Livewire untuk menampilkan data transaksi -->
    @livewire('order-table')
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- Buka modal jika ada query ?showModal=1 ---
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('showModal') === '1') {
            const modalElement = document.getElementById("grafikPemasukanModal");
            const modalInstance = new bootstrap.Modal(modalElement);
            modalInstance.show();
        }

        const labels = Array.from({
            length: 31
        }, (_, i) => (i + 1).toString());
        const cashData = @json($cashData);
        const midtransData = @json($midtransData);

        const ctx = document.getElementById("salse-revenue-modal").getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                        label: "Cash",
                        data: cashData,
                        backgroundColor: "rgba(0, 156, 255, .5)",
                        fill: true,
                    },
                    {
                        label: "Midtrans",
                        data: midtransData,
                        backgroundColor: "rgba(0, 156, 255, .3)",
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: 0,
                        max: 5000000,
                        ticks: {
                            stepSize: 250000,
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    }
                }
            }
        });
    });
</script>
