@extends('layouts.home')

@section('container')
    {{-- menghitung jumlah post --}}
    @if ($posts->count())
        <div class="row justify-content-center">
            <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px";>
                <div class="card-body text-center mb-2">
                    <div id="carouselExampleCaptions" class="carousel slide">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0"
                                class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                                aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner">
                            @foreach ($posts as $key => $post)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    @if ($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" class="d-block mx-auto"
                                            style="max-width: 200px; max-height: 200px;" alt="{{ $post->category->name }}">
                                    @else
                                        <img src="{{ asset('img/notavailable.png') }}" class="d-block mx-auto"
                                            style="max-width: 200px; max-height: 200px;" alt="Image Not Available">
                                    @endif
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5 class="text-dark">{{ $post->title }}</h5>
                                        <a href="/posts/{{ $post->slug }}"
                                            class="text-decoration-none btn btn-warning text-dark">Read
                                            more..</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="text-center fs-4">No post found.</p>
    @endif

    <div class="container-fluid mt-2 p-0">
        <div class="row justify-content-center">
            <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px";>
                @livewire('category-filter', ['tableNumber' => session('tableNumber')])
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pastikan DOM sudah siap
        var myCarouselElement = document.getElementById('carouselExampleCaptions');

        // Penting: Hanya inisialisasi jika elemen carousel ditemukan
        if (myCarouselElement) {
            // Cek juga apakah ada item di dalam carousel-inner
            var carouselInner = myCarouselElement.querySelector('.carousel-inner');
            if (carouselInner && carouselInner.children.length > 0) {
                // Inisialisasi carousel Bootstrap
                var carousel = new bootstrap.Carousel(myCarouselElement, {
                    interval: 5000, // Atur interval sesuai kebutuhan Anda
                    ride: 'carousel'
                });

                // Pastikan indikator diset aktif jika ada
                var firstItem = carouselInner.querySelector('.carousel-item.active');
                if (!firstItem && carouselInner.firstElementChild) {
                    carouselInner.firstElementChild.classList.add('active');
                }
                var indicators = myCarouselElement.querySelector('.carousel-indicators');
                if (indicators && indicators.children.length > 0 && !indicators.querySelector('.active')) {
                    indicators.firstElementChild.classList.add('active');
                }

            } else {
                console.warn('Carousel element found, but no inner items. Not initializing carousel.');
                // Anda bisa menyembunyikan carousel atau menampilkan pesan jika tidak ada item
                myCarouselElement.style.display = 'none';
            }
        } else {
            console.info('Carousel element #carouselExampleCaptions not found on this page.');
        }
    });

    // Penting: Pastikan Livewire scripts dimuat sebelum ini jika Anda menempatkannya di blade
    // window.Livewire.hook('element.initialized', (el, component) => { ... })
    // Jika ada bagian dari komponen Livewire yang menghapus dan menambahkan kembali carousel,
    // Anda mungkin perlu menginisialisasi ulang di Livewire hook.
</script>
