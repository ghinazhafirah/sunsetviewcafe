<div class="container-fluid p-0">
    {{-- Header dengan Flexbox untuk Penataan Elemen --}}
    <div class="card-header bg-warning d-flex justify-content-between align-items-center">

        {{-- Bagian Kiri/Tengah: Kategori atau Search Input --}}
        {{-- flex-grow-1 akan membuat div ini mengambil sisa ruang yang tersedia --}}
        <div class="flex-grow-1 d-flex align-items-center overflow-hidden px-2"> {{-- Atau ps-2 jika hanya butuh padding kiri --}}
            @if ($showSearch)
                {{-- Input Search: Muncul ketika $showSearch true --}}
                <div class="input-group flex-grow-1 me-2">
                    {{--
                        Perbaikan: Menggunakan wire:model.live.debounce.500ms
                        untuk pembaruan real-time dengan penundaan 500ms.
                        Menambahkan wire:keydown.enter="performSearch" agar Enter memicu pencarian.
                    --}}
                    <input type="text" wire:model.live.debounce.500ms="search" wire:keydown.enter="performSearch"
                        {{-- Tambahan ini untuk memicu pencarian dengan Enter --}} placeholder="Cari menu..." class="form-control form-control-sm rounded-s-md"
                        autofocus>
                    @if ($search)
                        <button class="btn btn-outline-secondary btn-sm rounded-e-md" type="button"
                            wire:click="$set('search', '')">
                            &times; {{-- Tombol clear search --}}
                        </button>
                    @endif
                </div>
            @else
                {{-- Navbar Kategori: Muncul ketika $showSearch false --}}
                <ul class="nav nav-pills card-header-pills d-flex flex-nowrap overflow-auto">
                    <li class="nav-item p-1">
                        <button wire:click="filterByCategory(null)"
                            class="btn {{ is_null($selectedCategory) ? 'btn-light' : 'btn-warning' }}">Semua</button>
                    </li>
                    @foreach ($categories as $category)
                        <li class="nav-item p-1">
                            <button wire:click="filterByCategory({{ $category->id }})"
                                class="btn {{ $selectedCategory == $category->id ? 'btn-light' : 'btn-warning' }}">
                                {{ $category->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Bagian Kanan: Tombol-tombol --}}
        <div class="d-flex align-items-center justify-content-end gap-2 ms-2">

            {{-- Tombol Toggle Search --}}
            <button class="btn btn-light text-dark btn-sm d-flex align-items-center justify-content-center rounded-md"
                style="width: 40px; height: 40px;" wire:click="toggleSearch">
                <i class="fa fa-search"></i>
            </button>

            {{-- Tombol Nomor Meja --}}
            @if ($tableNumber)
                <button
                    class="btn btn-light text-dark btn-sm d-flex align-items-center justify-content-center px-2 rounded-md"
                    style="height: 40px; min-width: auto; max-width: 120px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                    disabled>
                    <span class="m-0"><strong>Meja: {{ $tableNumber }}</strong></span>
                </button>
            @endif

            {{-- Cart Icon Badge --}}
            <button class="btn btn-light text-dark btn-sm d-flex align-items-center justify-content-center rounded-md"
                style="width: 40px; height: 40px;">
                {{-- @livewire('cart-icon-badge', ['tableNumber' => $tableNumber]) --}}
                @livewire('cart-icon-badge', [
                    'tableNumber' => $tableNumber,
                    'selectedCategory' => $selectedCategory ?? null, // <<< TERUSKAN INI
                    'search' => $search ?? null, // <<< TERUSKAN INI
                ])
            </button>

        </div>

    </div>


    {{-- Bagian Menu --}}
    <div id="menu-section" class="px-3 py-2">
        @forelse ($posts as $post)
            {{-- Gunakan wire:key untuk performa Livewire yang lebih baik di loop --}}
            <div class="card mb-2 shadow-sm border border-warning rounded-lg" wire:key="post-{{ $post->id }}">
                <div class="row g-0 align-items-center">
                    <div class="col-4 d-flex justify-content-center align-items-center p-2">
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid rounded"
                                alt="{{ $post->category->name }}" style="max-height: 80px; object-fit: contain;">
                        @else
                            <img src="{{ asset('img/notavailable.png') }}" class="img-fluid rounded"
                                alt="Image Not Available" style="max-height: 80px; object-fit: contain;">
                        @endif
                    </div>
                    <div class="col-8">
                        <div class="card-body p-2 d-flex flex-column justify-content-between h-100">
                            <div>
                                <h6 class="card-title mb-1 text-truncate">{{ $post->title }}</h6>
                                <p class="card-text text-muted small mb-2 text-truncate">{{ $post->excerpt }}</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                <span class="fw-bold small">Rp {{ number_format($post->price, 0, ',', '.') }}</span>
                                {{-- <a href="{{ route('posts.show', ['slug' => $post->slug, 'tableNumber' => $tableNumber]) }}"
                                    class="btn btn-outline-warning text-dark"> Add </a> --}}
                                <a href="{{ route('posts.show', [
                                    'slug' => $post->slug,
                                    'tableNumber' => $tableNumber,
                                    'selectedCategory' => $selectedCategory, // Tambahkan ini
                                    'search' => $search, // Tambahkan ini
                                ]) }}"
                                    class="btn btn-outline-warning text-dark"> Add </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card-body text-center py-4">
                <p>Menu tidak ditemukan!</p>
            </div>
        @endforelse
    </div>
</div>