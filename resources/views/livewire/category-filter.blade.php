<div class="container-fluid p-0">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-8">
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
            <div class="col-4 text-end">
                <a href="#" class="btn btn-light text-dark">Meja
                </a>
                <a href="/cart" class="btn btn-light py-2">
                    <i class="fas fa-shopping-cart" style="font-size:16px"></i>
                </a>
            </div>
        </div>
    </div>
    <div id="menu-section">
        @foreach ($posts as $post)
            <div class="card-body">
                <div class="row align-items-center border-bottom border-warning pb-2">
                    <div class="col-4">
                        <img src="https://images.unsplash.com/5/unsplash-kitsune-4.jpg?w=400&fit=max&ixid=eyJhcHBfaWQiOjEyMDd9&s={{ $post->category->name }}"
                            class="card-img-top img-fluid p-2 rounded" alt="{{ $post->category->name }}">
                    </div>
                    <div class="col-8">
                        <div class="card-body p-0">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->excerpt }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Rp {{ number_format($post->harga, 0, ',', '.') }}</span>
                                <a href="/posts/{{ $post->slug }}" class="btn btn-outline-warning text-dark">Add</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
