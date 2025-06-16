@extends('dashboard.layouts.main')

@section('container')
{{-- Debugging block (commented out) --}}
<div class="container-fluid pt-4 px-4">
        {{-- Menggunakan g-3 (jarak lebih kecil) dan memastikan alignment center --}}
        <div class="row g-3 align-items-stretch"> {{-- g-3 untuk jarak antar kolom, align-items-stretch untuk tinggi kolom sama --}}

            {{-- Card untuk Tombol Lihat Grafik (col-lg-3) --}}
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3"> {{-- Tambah mb-3 untuk margin bawah di breakpoint kecil --}}
                <button
                    class="rounded d-flex align-items-center justify-content-between p-3 border-0 w-100 shadow-sm text-dark h-100 custom-dashboard-card"
                    data-bs-toggle="modal" data-bs-target="#grafikPemasukanModal"
                    style="background-color: #f8f9fa; transition: background-color 0.3s ease;"
                    onmouseenter="this.style.backgroundColor='#ffc107'" onmouseleave="this.style.backgroundColor='#f8f9fa'">
                    <i class="fa fa-chart-line fa-2x text-primary flex-shrink-0"></i> {{-- flex-shrink-0 agar ikon tidak mengecil --}}
                    <div class="ms-3 text-start flex-grow-1"> {{-- flex-grow-1 agar teks mengambil sisa ruang --}}
                        <p class="mb-1 small">Lihat Grafik</p>
                        <h6 class="mb-0">Pemasukan</h6>
                    </div>
                </button>
            </div>

            {{-- Card: Total Hari Ini (Gabungan Cash & Digital) (col-lg-3) --}}
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-3 shadow-sm h-100 custom-dashboard-card">
                    <i class="fa fa-credit-card fa-2x text-primary flex-shrink-0"></i>
                    <div class="ms-3 text-end flex-grow-1"> {{-- text-end agar angka di kanan --}}
                        <p class="mb-1 small">Total Hari Ini</p>
                        <h6 class="mb-0">Rp. {{ number_format($totalHariIni, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>

            {{-- Card: Total Pemasukan Bulan Ini (Gabungan Cash & Digital) (col-lg-3) --}}
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-3 shadow-sm h-100 custom-dashboard-card">
                    <i class="fa fa-calendar-alt fa-2x text-warning flex-shrink-0"></i>
                    <div class="ms-3 text-end flex-grow-1">
                        <p class="mb-1 small">Total Bulan Ini</p>
                        <h6 class="mb-0">Rp. {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>

            {{-- Card: Total Pemasukan Keseluruhan (Kumulatif) (col-lg-3) --}}
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-3 shadow-sm h-100 custom-dashboard-card">
                    <i class="fa fa-chart-pie fa-2x text-danger flex-shrink-0"></i>
                    <div class="ms-3 text-end flex-grow-1">
                        <p class="mb-1 small">Total Keseluruhan</p>
                        <h6 class="mb-0">Rp. {{ number_format($totalKumulatifKeseluruhan, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>

        </div> {{-- Penutup row untuk card-card ringkasan --}}
    </div> {{-- Penutup container-fluid --}}

    {{-- Modal Grafik Pemasukan (tetap di luar .container-fluid) --}}
    <div class="modal fade" id="grafikPemasukanModal" tabindex="-1" aria-labelledby="chartIncomeLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chartIncomeLabel">Grafik Pemasukan Cash | Digital</h5>
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
                                @foreach (range(Carbon\Carbon::now()->year - 5, Carbon\Carbon::now()->year + 1) as $y)
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

    @livewire('order-table')
    
    <div class="pagination mt-3 d-flex justify-content-center">
        {{ $orders->links('vendor.pagination.index') }}
    </div>

@endsection

{{-- Pindahkan script ke bagian bawah file atau di dalam @push('scripts') jika Anda menggunakannya --}}
{{-- atau pastikan ditempatkan setelah elemen canvas tersedia di DOM --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('showModal') === '1') {
            const modalElement = document.getElementById("grafikPemasukanModal");
            const modalInstance = new bootstrap.Modal(modalElement);
            modalInstance.show();
        }

        const daysInMonth = {{ $daysInMonth ?? Carbon\Carbon::now()->daysInMonth }};
        const labels = Array.from({ length: daysInMonth }, (_, i) => (i + 1).toString());

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
                        borderColor: "rgba(0, 156, 255, 1)",
                        backgroundColor: "rgba(0, 156, 255, .5)",
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: "Digital (Midtrans)",
                        data: midtransData,
                        borderColor: "rgba(255, 193, 7, 1)",
                        backgroundColor: "rgba(255, 193, 7, .3)",
                        fill: true,
                        tension: 0.4
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: 0,
                        ticks: {
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
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    /* Custom CSS untuk merapikan card */
    .custom-dashboard-card .fa {
        /* Mengatur agar ikon terpusat secara vertikal dalam div flex */
        align-self: center;
        /* Tambahan margin-right jika ingin ikon sedikit menjauh dari teks */
        /* margin-right: 10px; */
    }

    .custom-dashboard-card div {
        /* Memastikan div teks mengambil sisa ruang */
        flex-grow: 1;
    }

    /* Opsi: sesuaikan tinggi card jika dirasa masih belum seragam
       Ini bisa diperlukan jika ada variasi tinggi yang tidak dapat dikontrol oleh h-100
       dan align-items-stretch sendirian, misalnya karena perbedaan font-rendering
       atau konten yang sangat dinamis. */
    .custom-dashboard-card {
        min-height: 80px; /* Contoh: Sesuaikan sesuai kebutuhan */
        max-height: 100px; /* Batasi tinggi maksimum */
    }
</style>