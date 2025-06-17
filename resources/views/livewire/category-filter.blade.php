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
                {{-- @livewire('cart-icon-badge', [
                    'tableNumber' => $tableNumber,
                    'selectedCategory' => $selectedCategory ?? null, // <<< TERUSKAN INI
                    'search' => $search ?? null, // <<< TERUSKAN INI
                ]) --}}
                <x-cart-icon-badge :tableNumber="$tableNumber" :selectedCategory="$selectedCategory" :search="$search" />
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
                                <div class="d-flex align-items-center">
                                    <span id="post-quantity-{{ $post->id }}"
                                        class="badge bg-warning text-dark me-2" style="display: none;">0x</span>

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
            </div>
        @empty
            <div class="card-body text-center py-4">
                <p>Menu tidak ditemukan!</p>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
   {{-- INI BISA TP KUDU RELOAD --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('[Cart Badge] DOM ready. Attaching event listeners.');

            function updateCartBadges(source = 'unknown') {
                console.log(`%c[Cart Badge] Memperbarui badge. Dipicu oleh: ${source}`,
                    'color: green; font-weight: bold;');

                try {
                    const cartData = sessionStorage.getItem('cart');
                    const mainCartBadge = document.getElementById(
                    'cart-item-count-badge'); // Mengganti nama untuk kejelasan

                    if (!mainCartBadge) {
                        console.warn(
                            '[Cart Badge] Tidak dapat menemukan elemen badge utama dengan ID "cart-item-count-badge". Menghentikan pembaruan.'
                        );
                        return;
                    }

                    const tableNumber = {{ $tableNumber ?? 'null' }};
                    if (tableNumber === null) {
                        console.error('[Cart Badge] Nomor Meja tidak tersedia untuk skrip.');
                        return;
                    }

                    const cart = cartData ? JSON.parse(cartData) : {};
                    const currentTableCartItems = cart[tableNumber] && cart[tableNumber].items ? cart[tableNumber]
                        .items : {};

                    // --- Logika untuk Cart Icon Badge Utama (total item unik di seluruh keranjang) ---
                    let totalUniqueItemsInCart = Object.keys(currentTableCartItems).length;

                    if (totalUniqueItemsInCart > 0) {
                        mainCartBadge.style.display = 'inline-block';
                        mainCartBadge.textContent = totalUniqueItemsInCart > 99 ? '99+' : totalUniqueItemsInCart;
                        console.log(`[Cart Badge] Badge utama diperbarui: ${mainCartBadge.textContent}`);
                    } else {
                        mainCartBadge.style.display = 'none';
                        console.log('[Cart Badge] Badge utama disembunyikan karena kosong.');
                    }


                    // --- Logika untuk Badge Kuantitas Individual per Item (hanya untuk item yang terlihat) ---
                    const postQuantityBadges = document.querySelectorAll('[id^="post-quantity-"]');

                    postQuantityBadges.forEach(badgeElement => {
                        const postId = badgeElement.id.replace('post-quantity-', '');
                        const itemInCart = currentTableCartItems[postId];

                        if (itemInCart && itemInCart.quantity > 0) {
                            badgeElement.textContent = itemInCart.quantity;
                            badgeElement.style.display = 'inline-block';
                        } else {
                            badgeElement.textContent = '0';
                            badgeElement.style.display = 'none';
                        }
                    });

                } catch (error) {
                    console.error('[Cart Badge] Terjadi kesalahan tak terduga:', error);
                }
                console.log('%c[Cart Badge] Siklus pembaruan selesai.', 'color: green;');
            }

            // Panggil sekali saat DOM halaman sepenuhnya dimuat.
            updateCartBadges('DOMContentLoaded');

            // Panggil saat pengguna menavigasi kembali ke halaman (e.g., dengan tombol kembali browser).
            window.addEventListener('pageshow', () => {
                updateCartBadges('pageshow');
            });

            // Dengarkan event kustom 'sessionCartUpdated' (dari halaman detail produk atau penambahan/pengurangan item)
            window.addEventListener('sessionCartUpdated', () => {
                updateCartBadges('sessionCartUpdated');
            });

            // Dengarkan event 'updateCartBadges' yang dipicu dari Livewire (saat kategori/pencarian berubah)
            Livewire.on('updateCartBadges', () => {
                updateCartBadges('Livewire Dispatch');
            });
        });
    </script> --}}

      <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('[Cart Badge] DOM ready. Attaching event listeners.');

            function updateCartBadges(source = 'unknown') {
                console.log(`%c[Cart Badge] Memperbarui badge. Dipicu oleh: ${source}`,
                    'color: green; font-weight: bold;');

                try {
                    const cartData = sessionStorage.getItem('cart');
                    const mainCartBadge = document.getElementById('cart-item-count-badge');

                    if (!mainCartBadge) {
                        console.warn(
                            '[Cart Badge] Tidak dapat menemukan elemen badge utama dengan ID "cart-item-count-badge". Menghentikan pembaruan.'
                        );
                        return;
                    }

                    const tableNumber = {{ $tableNumber ?? 'null' }};
                    if (tableNumber === null) {
                        console.error('[Cart Badge] Nomor Meja tidak tersedia untuk skrip.');
                        return;
                    }

                    const cart = cartData ? JSON.parse(cartData) : {};
                    const currentTableCartItems = cart[tableNumber] && cart[tableNumber].items ? cart[tableNumber]
                        .items : {};

                    // --- Logika untuk Cart Icon Badge Utama (total item unik di seluruh keranjang) ---
                    let totalUniqueItemsInCart = Object.keys(currentTableCartItems).length;

                    if (totalUniqueItemsInCart > 0) {
                        mainCartBadge.style.display = 'inline-block';
                        mainCartBadge.textContent = totalUniqueItemsInCart > 99 ? '99+' : totalUniqueItemsInCart;
                        console.log(`[Cart Badge] Badge utama diperbarui: ${mainCartBadge.textContent}`);
                    } else {
                        mainCartBadge.style.display = 'none';
                        console.log('[Cart Badge] Badge utama disembunyikan karena kosong.');
                    }


                    // --- Logika untuk Badge Kuantitas Individual per Item (hanya untuk item yang terlihat) ---
                    const postQuantityBadges = document.querySelectorAll('[id^="post-quantity-"]');

                    // Penting: Pastikan kita mengiterasi semua badge yang ada di DOM saat ini.
                    postQuantityBadges.forEach(badgeElement => {
                        const postId = badgeElement.id.replace('post-quantity-', '');
                        const itemInCart = currentTableCartItems[postId];

                        if (itemInCart && itemInCart.quantity > 0) {
                            badgeElement.textContent = `${itemInCart.quantity}x`; // Tambahkan 'x' agar jelas
                            badgeElement.style.display = 'inline-block';
                        } else {
                            badgeElement.textContent = '0x'; // Setel kembali ke '0x' saat disembunyikan
                            badgeElement.style.display = 'none';
                        }
                    });

                } catch (error) {
                    console.error('[Cart Badge] Terjadi kesalahan tak terduga:', error);
                }
                console.log('%c[Cart Badge] Siklus pembaruan selesai.', 'color: green;');
            }

            // Panggil sekali saat DOM halaman sepenuhnya dimuat.
            updateCartBadges('DOMContentLoaded');

            // Panggil saat pengguna menavigasi kembali ke halaman (e.g., dengan tombol kembali browser).
            window.addEventListener('pageshow', () => {
                updateCartBadges('pageshow');
            });

            // Dengarkan event kustom 'sessionCartUpdated' (dari halaman detail produk atau penambahan/pengurangan item)
            // Ini dipicu saat keranjang di session storage berubah.
            window.addEventListener('sessionCartUpdated', () => {
                updateCartBadges('sessionCartUpdated');
            });

            // Dengarkan event 'menuUpdatedFromLivewire' yang dipicu dari Livewire
            // Ini dipicu setiap kali komponen Livewire merender ulang (misalnya, ganti kategori/pencarian).
            window.addEventListener('menuUpdatedFromLivewire', () => {
                updateCartBadges('Livewire Dispatch');
            });
        });
    </script>
@endpush
