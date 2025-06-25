@extends('layouts.main')

@section('container')
    {{-- cek apakah ada post --}}
    @if ($posts->count())
        <div class="row justify-content-center">
            <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px;">
                <div class="card-body text-center mb-2">
                    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                        {{-- Indikator dinamis --}}
                        <div class="carousel-indicators">
                            @foreach ($posts as $index => $post)
                                <button type="button" data-bs-target="#carouselExampleCaptions"
                                    data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"
                                    aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $index + 1 }}"></button>
                            @endforeach
                        </div>

                        {{-- Isi carousel --}}
                        <div class="carousel-inner">
                            @foreach ($posts as $index => $post)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    @if ($post->image)
                                        <img src="{{ asset('storage/' . $post->image) }}" class="d-block mx-auto"
                                            style="max-width: 200px; max-height: 200px;" alt="{{ $post->category->name }}">
                                    @else
                                        <img src="{{ asset('img/notavailable.webp') }}" class="d-block mx-auto"
                                            style="max-width: 200px; max-height: 200px;" alt="Image Not Available">
                                    @endif
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5 class="text-dark">{{ $post->title }}</h5>
                                        <a href="/posts/{{ $post->slug }}"
                                            class="text-decoration-none btn btn-warning text-dark">Read more..</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Navigasi tombol --}}
                        {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="text-center fs-4">No post found.</p>
    @endif

    {{-- Livewire category filter --}}
    <div class="container-fluid mt-2 p-0">
        <div class="row justify-content-center">
            <div class="card col-12 col-md-10 col-lg-8 p-0" style="width: 700px;">
                @livewire('category-filter', ['tableNumber' => session('tableNumber')])
            </div>
        </div>
    </div>
@endsection

{{-- Inisialisasi carousel di push script --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const myCarouselElement = document.getElementById('carouselExampleCaptions');
            if (myCarouselElement) {
                const carouselInner = myCarouselElement.querySelector('.carousel-inner');
                if (carouselInner && carouselInner.children.length > 0) {
                    const carousel = new bootstrap.Carousel(myCarouselElement, {
                        interval: 5000,
                        ride: 'carousel',
                        wrap: true // aktifkan looping kembali ke awal
                    });

                    // Jaga-jaga jika class active belum disetel
                    const firstItem = carouselInner.querySelector('.carousel-item.active');
                    if (!firstItem && carouselInner.firstElementChild) {
                        carouselInner.firstElementChild.classList.add('active');
                    }

                    const indicators = myCarouselElement.querySelector('.carousel-indicators');
                    if (indicators && indicators.children.length > 0 && !indicators.querySelector('.active')) {
                        indicators.firstElementChild.classList.add('active');
                    }

                } else {
                    console.warn('Carousel element found, but no inner items.');
                    myCarouselElement.style.display = 'none';
                }
            } else {
                console.info('Carousel element #carouselExampleCaptions not found on this page.');
            }
        });
    </script>
@endpush
