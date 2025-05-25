{{-- @php dd($tableNumber); @endphp --}}


<div class="container-fluid p-0">
    <div class="card-header bg-warning">
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-7">
                {{-- navbar bagian kategori --}}
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
            </div>
            <div class="col-5 text-end">
                @if (session()->has('tableNumber'))
                    <div class="btn btn-light text-dark">
                        <h6>Meja: {{ session('tableNumber') ?? 'Tidak ada' }}</h6>
                    </div>
                @endif
                {{-- <a href="{{ route('cart.show', ['table' => session('tableNumber')]) }}" class="btn btn-light py-2">
                    <i class="fas fa-shopping-cart" style="font-size:16px"></i>
                </a> --}}
                @livewire('cart-icon-badge', ['tableNumber' => session('tableNumber')])
            </div>
        </div>
    </div>
    <div id="menu-section">
        @foreach ($posts as $post)
            <div class="card-body">
                <div class="row align-items-center border-bottom border-warning pb-1">
                    <div class="col-4">
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top w-70 mx-auto"
                                alt="{{ $post->category->name }}" class="img-fluid menu-image">
                        @else
                            {{-- tambahan ketika menu gada gambarnya --}}
                            <img src="{{ asset('img/notavailable.png') }}" class="card-img-top w-70 mx-auto"
                                alt="Image Not Available" class="img-fluid menu-image">
                        @endif
                    </div>
                    <div class="col-8">
                        <div class="card-body p-0">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->excerpt }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Rp {{ number_format($post->price, 0, ',', '.') }}</span>
                                <a href="{{ route('posts.show', ['slug' => $post->slug, 'tableNumber' => session('tableNumber') ?? 1]) }}"
                                    class="btn btn-outline-warning text-dark"> Add </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
